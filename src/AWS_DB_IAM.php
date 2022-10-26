<?php

namespace AWSM;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use Aws\Rds\AuthTokenGenerator;
use Josantonius\Json\Exceptions\GetFileException;
use Josantonius\Json\Exceptions\JsonErrorException;
use Josantonius\Json\Exceptions\UnavailableMethodException;

class AWS_DB_IAM
{
    /**
     * @param string $DB_HOST
     * @param string $DB_USERNAME
     * @param file $file
     * @param string $AWS_DEFAULT_REGION
     * @param string $AWS_ACCESS_ID
     * @param string $AWS_SECRET_KEY
     * @throws GetFileException
     * @throws JsonErrorException
     * @throws UnavailableMethodException
     */
    public static function lookup(string $DB_HOST, string $DB_USERNAME, file $file, string $AWS_DEFAULT_REGION, string $AWS_ACCESS_ID, string $AWS_SECRET_KEY): void
    {
        $provider = CredentialProvider::fromCredentials(new Credentials($AWS_ACCESS_ID, $AWS_SECRET_KEY));

        $RdsAuthGenerator = new AuthTokenGenerator($provider);

        $db_token = $RdsAuthGenerator->createToken($DB_HOST . ':' . 3306, $AWS_DEFAULT_REGION, $DB_USERNAME);
        $db_token_name = 'db_token' . $DB_HOST;

        $file->add($db_token_name, $db_token);
    }
}
