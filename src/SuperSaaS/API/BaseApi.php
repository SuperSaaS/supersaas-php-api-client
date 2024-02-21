<?php namespace SuperSaaS\API;

use SuperSaaS\Client;
use SuperSaaS\SSS_Exception;

class BaseApi
{
    const string INTEGER_REGEX = "/\A[0-9]+\Z/";
    const string DATETIME_REGEX = "/\A\d{4}-\d{1,2}-\d{1,2}\s\d{1,2}:\d{1,2}:\d{1,2}\Z/";
    const string PROMOTION_REGEX = "/^[0-9a-zA-Z]+$/";


    public Client $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @throws SSS_Exception
     */
    protected function validateId($value): int|string
    {
        if (is_numeric($value)) {
            return $value;
        } else if (preg_match(self::INTEGER_REGEX, $value)) {
            return intval($value);
        } else {
            throw new SSS_Exception("Invalid id parameter: " . $value . ". Provide a integer value.");
        }
    }

    /**
     * @throws SSS_Exception
     */
    protected function validateNumber($value): int|string
    {
        return $this->validateId($value);
    }

    /**
     * @throws SSS_Exception
     */
    protected function validatePresent($value)
    {
        if (!isset($value)) {
            throw new SSS_Exception("Required parameter is missing.");
        } else {
            return $value;
        }
    }

    /**
     * @throws SSS_Exception
     */
    function validatePromotion($value): string
    {
        if (!is_string($value) || !strlen($value) || !preg_match(self::PROMOTION_REGEX, $value)) {
            throw new SSS_Exception('Required parameter promotional code not found or contains other than alphanumeric characters.');
        }

        return $value;
    }

    /**
     * @throws SSS_Exception
     */
    protected function validateUser($value)
    {
        if ($value === null) {
            return;
        }

        if (!is_int($value) && !is_string($value)) {
            throw new SSS_Exception("Invalid user id parameter: {$value}.");
        }

        return $value;
    }

    /**
     * @throws SSS_Exception
     */
    protected function validateDuplicate($value): string
    {
        $allowedValues = ['ignore', 'raise'];

        if (!is_string($value) || !in_array($value, $allowedValues, true)) {
            throw new SSS_Exception("Required parameter duplicate can only be 'ignore' or 'raise'.");
        }

        return $value;
    }

    /**
     * @throws SSS_Exception
     */
    function validateNotFound($value): string
    {
        $allowedValues = ['error', 'ignore'];

        if (!is_string($value) || !in_array($value, $allowedValues, true)) {
            throw new SSS_Exception("Required parameter notfound can only be 'error' or 'ignore'.");
        }

        return $value;
    }

    /**
     * @throws SSS_Exception
     */
    protected function validateDatetime($value)
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        } else if (preg_match(self::DATETIME_REGEX, $value.'')) {
            return $value;
        } else {
            throw new SSS_Exception("Invalid datetime parameter: " . $value . ". Provide a DateTime object or formatted 'YYYY-DD-MM HH:MM:SS' string.");
        }
    }

    /**
     * @throws SSS_Exception
     */
    protected function validateName($value): string|null
    {
        if ($value !== null && (!is_string($value) || strlen($value) === 0)) {
            throw new SSS_Exception('Required parameter name is missing.');
        }

        return $value;
    }


    /**
     * @throws SSS_Exception
     */
    protected function validateOptions($value, $options): void
    {
        if (!in_array($value, $options, true)) {
            throw new SSS_Exception("Invalid option parameter: {$value}. Must be one of " . implode(', ', $options));
        }
    }
}