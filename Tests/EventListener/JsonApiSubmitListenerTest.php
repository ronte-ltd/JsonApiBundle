<?php

namespace RonteLtd\JsonApiBundle\Tests\EventListener;

use RonteLtd\JsonApiBundle\EventListener\JsonApiSubmitListener;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class JsonApiSubmitListenerTest extends WebTestCase
{
    public function testOnKernelRequest()
    {
        // curl -X POST \
        //     -H "Content-Type: application/vnd.api+json" \
        //     -H "Accept: application/vnd.api+json" \
        //     --data '{"data": { ... }}' \
        //     https://api.example.com/api/v1/fake-path
        $request = Request::create(
            'https://api.example.com/api/v1/fake-path',
            'POST',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/vnd.api+json',
                'HTTP_ACCEPT' => 'application/vnd.api+json',
            ],
            <<<JSON
{
    "data": {
        "type": "example-form",
        "attributes": {
            "field1": "value1",
            "field2": "value2"
        }
    }
}
JSON
        );

        $event = new GetResponseEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $listener = new JsonApiSubmitListener();
        $listener->onKernelRequest($event);

        // Magic! =)

        $this->assertEquals(
            $request->headers->get('Content-Type'),
            'application/x-www-form-urlencoded'
        );

        $this->assertEquals(
            $request->request->all(),
            [
                'example-form' => [
                    'field1' => 'value1',
                    'field2' => 'value2',
                ],
            ]
        );

        // For now, you can use internal forms.
        //
        // $form = $this->createForm(...);
        // $form->handleRequest($request);
        //
        // if ($form->isValid()) { ... }
    }

    public function testInvalidJsonOnKernelRequest()
    {
        $request = Request::create(
            'https://api.example.com/api/v1/fake-path',
            'POST',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/vnd.api+json',
                'HTTP_ACCEPT' => 'application/vnd.api+json',
            ],
            <<<JSON
123;
JSON
        );

        $event = new GetResponseEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $listener = new JsonApiSubmitListener();

        try {
            $listener->onKernelRequest($event);

            $this->fail();
        } catch (BadRequestHttpException $e) {
            $this->assertTrue(true);
        }
    }

    public function testNonJsonApiFormatOnKernelRequest()
    {
        $request = Request::create(
            'https://api.example.com/api/v1/fake-path',
            'POST',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/vnd.api+json',
                'HTTP_ACCEPT' => 'application/vnd.api+json',
            ],
            <<<JSON
{
    "field1": "value1",
    "field2": "value2"
}
JSON
        );

        $event = new GetResponseEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $listener = new JsonApiSubmitListener();

        try {
            $listener->onKernelRequest($event);

            $this->fail();
        } catch (BadRequestHttpException $e) {
            $this->assertTrue(true);
        }
    }
}