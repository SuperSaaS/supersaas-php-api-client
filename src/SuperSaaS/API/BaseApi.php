<?php namespace SuperSaaS\API;

use SuperSaaS\SSS_Exception;

class BaseApi
{
    const INTEGER_REGEX = "/\A[0-9]+\Z/\\";
    const DATETIME_REGEX = "/\A\d{4}-\d{1,2}-\d{1,2}\s\d{1,2}:\d{1,2}:\d{1,2}\Z/";

    /**
     * @var \SuperSaaS\Client
     */
    public $client;

    public function __construct ($client) {
        $this->client = $client;
    }

    protected function validateId ($value) {
        if (is_numeric($value)) {
            return $value;
        } else if (preg_match(self::INTEGER_REGEX, $value)) {
            return intval($value);
        } else {
            throw new \Exception(new SSS_Exception("Invalid id parameter: " . $value . ". Provide a integer value."));
        }
    }

    protected function validateNumber ($value) {
        return $this->validateId($value);
    }

    protected function validatePresent ($value) {
        if (empty($value)) {
            throw new \Exception(new SSS_Exception("Required parameter is missing."));
        } else {
            return $value;
        }
    }

    protected function validateDatetime ($value) {
        if ($value instanceof \DateTime) {
            return $value;
        } else if (preg_match(self::DATETIME_REGEX, $value.'')) {
            return \DateTime::createFromFormat('Y-d-m G:i:s', $value);
        } else {
            throw new \Exception(new SSS_Exception("Invalid datetime parameter: " . $value . ". Provide a DateTime object or formatted 'YYYY-DD-MM HH:MM:SS' string."));
        }
    }

    protected function validateOptions ($value, $options) {
        if (in_array($value, $options)) {

        } else {
            throw new \Exception(new SSS_Exception("Invalid option parameter: " . $value . ". Must be one of " . join(', ', $options)));
        }
    }
}