<?php

namespace StellaPortal;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use Aws\SecretsManager\SecretsManagerClient;
use Aws\Rds\AuthTokenGenerator;
use Closure;

class Secrets
{
    public static function load()
    {
        $file = self::secFile();

        if (!file_exists($file)) {
            file_put_contents($file, '');
        }

        $secs = self::secContents();

        if (!isset($secs['WTIME'])) {
            self::update();
        } else {
            if ($secs['WTIME'] < time()) {
                self::update();
            }
        }
    }

    public static function lookup($secName)
    {
        $secs = self::secContents();
        return $secs[$secName];
    }

    public static function update()
    {
        $provider = self::aws_creds();

        $client = new SecretsManagerClient([
            'version' => '2017-10-17',
            'region' => getenv('AWS_DEFAULT_REGION'),
            'credentials' => $provider
        ]);

        $secretNames = array(
            'Mailgun',
            'Asana',
            'Twilio',
            'Stella_Portal',
        );

        //15 Minute Lifetime
        $secret_array['WTIME'] = time() + 900;

        foreach ($secretNames as $secretName) {
            $secret_string = $client->getSecretValue(['SecretId' => $secretName])['SecretString'];
            $secrets = (array) json_decode($secret_string);

            foreach ($secrets as $name => $value) {
                $secret_array[$name] = $value;
            }
        }

        if (Helper::dstatus() == 'production') {
            $db_token = self::db_token(getenv('DB_HOST'), getenv('AWS_DEFAULT_REGION'));
            $secret_array['DB_PASSWORD'] = $db_token;
        }

        $file = self::secFile();
        file_put_contents($file, json_encode($secret_array));
    }

    private static function db_token($endpoint, $region): string
    {
        /**
        *** Docs: https://gist.github.com/sators/38dbe25f655f1c783cb2c49e9873d58a
        */

        $provider = self::aws_creds();

        $RdsAuthGenerator = new AuthTokenGenerator($provider);

        return $RdsAuthGenerator->createToken($endpoint . ':' . 3306, $region, 'stellapm_portal');
    }

    private static function aws_creds(): callable|Closure
    {
        return CredentialProvider::fromCredentials(new Credentials(getenv('AWS_ACCESS_ID'), getenv('AWS_SECRET_KEY')));
    }

    private static function secContents(): array
    {
        $file = self::secFile();
        $contents_json = file_get_contents($file);
        return (array) json_decode($contents_json);
    }

    private static function secFile(): string
    {
        return __DIR__ . '/.secenv.json';
    }
}
