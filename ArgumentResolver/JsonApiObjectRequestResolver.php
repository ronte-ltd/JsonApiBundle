<?php

namespace RonteLtd\JsonApiBundle\ArgumentResolver;

use RonteLtd\JsonApiBundle\Model\AbstractJsonApiObjectRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @author Sergey Kodentsov <soulkoden@gmail.com>
 */
class JsonApiObjectRequestResolver implements ArgumentValueResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        if (!is_subclass_of($argument->getType(), AbstractJsonApiObjectRequest::class)) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        /** @var string $jsonApiObjectRequestInstanceClassName */
        $jsonApiObjectRequestInstanceClassName = $argument->getType();

        /** @var AbstractJsonApiObjectRequest $jsonApiObjectRequestInstance */
        $jsonApiObjectRequestInstance = new $jsonApiObjectRequestInstanceClassName;

        /** @var array|null $submittedData */
        $submittedData = json_decode($request->getContent(), true);

        if (json_last_error()) {
            throw new BadRequestHttpException(sprintf(
                'JSON parse error: "%s."',
                json_last_error_msg()
            ));
        }

        $jsonApiObjectRequestInstance->mergeWithArray($submittedData);

        yield $jsonApiObjectRequestInstance;
    }
}