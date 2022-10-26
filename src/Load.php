<?php

namespace AWSM;

use Exception;
use Josantonius\Json\Exceptions\GetFileException;
use Josantonius\Json\Exceptions\JsonErrorException;
use Josantonius\Json\Exceptions\UnavailableMethodException;

class Load
{
    protected string $path;
    protected File $file;
    protected string $AWS_DEFAULT_REGION;
    protected string $AWS_ACCESS_ID;
    protected string $AWS_SECRET_KEY;

    /**
     * @param string $path
     * @param string $AWS_DEFAULT_REGION
     * @param string $AWS_ACCESS_ID
     * @param string $AWS_SECRET_KEY
     * @throws Exception
     */
    public function __construct(string $path, string $AWS_DEFAULT_REGION, string $AWS_ACCESS_ID, string $AWS_SECRET_KEY)
    {
        $this->file = new File($path);

        $this->AWS_DEFAULT_REGION = $AWS_DEFAULT_REGION;
        $this->AWS_ACCESS_ID = $AWS_ACCESS_ID;
        $this->AWS_SECRET_KEY = $AWS_SECRET_KEY;
    }

    /**
     * @param array $names
     * @throws JsonErrorException
     * @throws UnavailableMethodException
     * @throws GetFileException
     */
    public function secrets(array $names): void
    {
        $time_p = time() + 86400;

        $last_update = $this->file->retrieve('AWSM-Secrets-Updated');

        if($last_update < time()) {
            foreach ($names as $name) {
                AWS_Secrets::lookup($name, $this->file, $this->AWS_DEFAULT_REGION, $this->AWS_ACCESS_ID, $this->AWS_SECRET_KEY);
            }

            $this->file->add('AWSM-Secrets-Expire', $time_p);
        }
    }

    /**
     * @param string $DB_HOST
     * @param string $DB_USERNAME
     * @throws UnavailableMethodException
     * @throws JsonErrorException
     * @throws GetFileException
     */
    public function db(string $DB_HOST, string $DB_USERNAME): void
    {
        $time_p = time() + 900;

        $last_update = $this->file->retrieve('AWSM-DB-IAM-Updated');

        if($last_update < time()) {
            AWS_DB_IAM::lookup($DB_HOST, $DB_USERNAME, $this->file, $this->AWS_DEFAULT_REGION, $this->AWS_ACCESS_ID, $this->AWS_SECRET_KEY);

            $this->file->add('AWSM-DBToken-Expire', $time_p);
        }
    }
}
