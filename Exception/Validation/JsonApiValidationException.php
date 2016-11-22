<?php

namespace RonteLtd\JsonApiBundle\Exception\Validation;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @author Sergey Kodentsov <soulkoden@gmail.com>
 */
class JsonApiValidationException extends \RuntimeException
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $errors;

    /**
     * @param ConstraintViolationListInterface $errors
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        ConstraintViolationListInterface $errors,
        $message = 'Validation error.',
        $code = 0,
        \Throwable $previous = null
    )
    {
        $this->errors = $errors;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getErrors()
    {
        return $this->errors;
    }
}