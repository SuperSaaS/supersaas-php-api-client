<?php namespace SuperSaaS;

class SSS_Exception extends \Exception
{
    /**
     * @var array Error objects as returned by the server in the `errors` key of the response body.
     */
    private array $errors;

    /**
     * @param string $message
     * @param int $code HTTP status code for failed requests, 0 otherwise.
     * @param \Throwable|null $previous
     * @param array $errors Parsed `errors` of the response body, if any.
     */
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null, array $errors = [])
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    /**
     * The errors reported by the server, as parsed JSON. Empty when the response had none.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
