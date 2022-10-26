<?php

namespace AWSM;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use Aws\SecretsManager\SecretsManagerClient;
use Josantonius\Json\Exceptions\GetFileException;
use Josantonius\Json\Exceptions\JsonErrorException;
use Josantonius\Json\Exceptions\UnavailableMethodException;

class AWS_Secrets
{
    /**
     * @param string $secretName
     * @param file $file
     * @param string $AWS_DEFAULT_REGION
     * @param string $AWS_ACCESS_ID
     * @param string $AWS_SECRET_KEY
     * @throws GetFileException
     * @throws JsonErrorException
     * @throws UnavailableMethodException
     */
    public static function lookup(string $secretName, file $file, string $AWS_DEFAULT_REGION, string $AWS_ACCESS_ID, string $AWS_SECRET_KEY): void
    {
        $provider = CredentialProvider::fromCredentials(new Credentials($AWS_ACCESS_ID, $AWS_SECRET_KEY));

        $client = new SecretsManagerClient([
            'version' => '2017-10-17',
            'region' => $AWS_DEFAULT_REGION,
            'credentials' => $provider
        ]);

        $secret_string = $client->getSecretValue(['SecretId' => $secretName])['SecretString'];
        $secrets = (array) json_decode($secret_string);

        foreach ($secrets as $name => $value) {
            $file->add($name, $value);
        }
    }
}
