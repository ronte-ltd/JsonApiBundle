<?php

namespace RonteLtd\JsonApiBundle\Exception\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * JsonApiFormException
 *
 * @author Sergey Kodentsov <soulkoden@gmail.com>
 */
class JsonApiFormException extends HttpException
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @param FormInterface $form
     * @param int|null $statusCode
     * @param string $message
     * @param \Exception|null $previous
     * @param array $headers
     * @param int $code
     */
    public function __construct(
        FormInterface $form,
        $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY,
        $message = 'Invalid form data.',
        \Exception $previous = null,
        array $headers = [],
        $code = 0)
    {
        $this->form = $form;

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }
}