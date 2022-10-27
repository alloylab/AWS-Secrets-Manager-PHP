<?php

namespace AWSM;

use AWSM\File\Json;
use AWSM\File\Json\Exceptions\GetFileException;
use AWSM\File\Json\Exceptions\JsonErrorException;
use AWSM\File\Json\Exceptions\CreateFileException;
use AWSM\File\Json\Exceptions\CreateDirectoryException;
use AWSM\File\Json\Exceptions\UnavailableMethodException;
use Exception;

class File
{
    protected string $path;
    protected Json $config;

    /**
     * @param string $path
     * @throws Exception
     */
    public function __construct(string $path = '/var/secrets.json')
    {
        try {
            $this->config = new Json($path);

            if(!$this->config->exists()) {
                $this->config->set();
            }
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @throws CreateDirectoryException
     * @throws CreateFileException
     * @throws GetFileException
     * @throws JsonErrorException
     * @throws UnavailableMethodException
     */
    public function add(string $key, string $value): void
    {
        try {
            $this->merge($key, base64_encode($value));
        } catch (GetFileException $e) {
            throw new GetFileException($e);
        } catch (JsonErrorException) {
            throw new JsonErrorException();
        } catch (UnavailableMethodException $e) {
            throw new UnavailableMethodException($e);
        }
    }

    /**
     * @param string $key
     * @return string
     * @throws GetFileException
     * @throws JsonErrorException
     */
    public function retrieve(string $key): string
    {
        try {
            if(isset($this->config->get()[$key])) {
                return base64_decode($this->config->get()[$key]);
            } else {
                return '';
            }
        } catch (GetFileException $e) {
            throw new GetFileException($e);
        } catch (JsonErrorException) {
            throw new JsonErrorException();
        }
    }

    /**
     * @param string $file
     * @param string $key
     * @return string
     * @throws GetFileException
     * @throws JsonErrorException
     */
    public static function st_retrieve(string $file, string $key): string
    {
        try {
            $json = new Json($file);
            if(isset($json->get()[$key])) {
                return base64_decode($json->get()[$key]);
            } else {
                return '';
            }
        } catch (GetFileException $e) {
            throw new GetFileException($e);
        } catch (JsonErrorException) {
            throw new JsonErrorException();
        }
    }

    /**
     * @param string $key
     * @throws CreateDirectoryException
     * @throws CreateFileException
     * @throws GetFileException
     * @throws JsonErrorException
     * @throws UnavailableMethodException
     */
    public function delete(string $key): void
    {
        try {
            $this->merge($key, '');
        } catch (GetFileException $e) {
            throw new GetFileException($e);
        } catch (JsonErrorException) {
            throw new JsonErrorException();
        } catch (UnavailableMethodException $e) {
            throw new UnavailableMethodException($e);
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @throws CreateDirectoryException
     * @throws CreateFileException
     * @throws GetFileException
     * @throws JsonErrorException
     * @throws UnavailableMethodException
     */
    private function merge(string $key, string $value): void
    {

        try {
            $this->config->merge([$key => $value]);
        } catch (GetFileException $e) {
            throw new GetFileException($e);
        } catch (JsonErrorException) {
            throw new JsonErrorException();
        } catch (UnavailableMethodException $e) {
            throw new UnavailableMethodException($e);
        }

    }

    /**
     * @param string $key
     * @param string $value
     * @throws CreateDirectoryException
     * @throws CreateFileException
     * @throws GetFileException
     * @throws JsonErrorException
     * @throws UnavailableMethodException
     */
    private function push(string $key, string $value): void
    {

        try {
            $this->config->push([$key => $value]);
        } catch (GetFileException $e) {
            throw new GetFileException($e);
        } catch (JsonErrorException) {
            throw new JsonErrorException();
        } catch (UnavailableMethodException $e) {
            throw new UnavailableMethodException($e);
        }

    }

    /**
     * @param string $key
     * @param string $value
     * @throws CreateDirectoryException
     * @throws CreateFileException
     * @throws JsonErrorException
     * @throws UnavailableMethodException
     */
    private function set(string $key, string $value): void
    {
        try {
            $this->config->set([$key => $value]);
        } catch (CreateDirectoryException $e) {
            throw new CreateDirectoryException($e);
        } catch (CreateFileException $e) {
            throw new CreateFileException($e);
        } catch (UnavailableMethodException $e) {
            throw new UnavailableMethodException($e);
        }
    }

    /**
     * @throws JsonErrorException
     * @throws GetFileException
     */
    private function get(): array
    {
        try {
            return $this->config->get();
        } catch (GetFileException $e) {
            throw new GetFileException($e);
        } catch (JsonErrorException) {
            throw new JsonErrorException();
        }
    }
}