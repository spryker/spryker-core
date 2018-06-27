<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Plugin;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Plugin\Rest\GlueControllerListenerPlugin;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestTestAttributesTransfer;
use SprykerTest\Glue\GlueApplication\Stub\TestsResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Plugin
 * @group GlueControllerFilterPluginTest
 * Add your own group annotations below this line
 */
class GlueControllerFilterPluginTest extends Unit
{
    /**
     * @return void
     */
    protected const URL_TEST_RESOURCE = 'http://domain.tld/tests/1';

    /**
     * @var array
     */
    protected $headers = [
        'HTTP_ACCEPT' => 'application/vnd.api+json',
        'HTTP_CONTENT-TYPE' => 'application/vnd.api+json',
        'HTTP_ACCEPT-LANGUAGE' => 'DE',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        RequestConstantsInterface::ATTRIBUTE_RESOURCE_FQCN => RestTestAttributesTransfer::class,
        RequestConstantsInterface::ATTRIBUTE_TYPE => 'tests',
    ];

    /**
     * @return void
     */
    protected const TESTS_POST_DATA = '{"data":{"type":"tests","attributes":{"attribute1":"1", "attribute2": "2"}}}';

    /**
     * @return void
     */
    public function testFilterWhenUnsupportedMediaTypeProvidedShouldReturnError(): void
    {
        $request = Request::create(static::URL_TEST_RESOURCE, Request::METHOD_GET);

        $response = $this->createGlueControllerListenerPlugin()
            ->filter(
                new TestsResourceController(),
                'get',
                $request
            );

        $this->assertEquals(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testFilterWhenAllHeadersProviderShouldReturnSuccess(): void
    {
        $request = Request::create(
            static::URL_TEST_RESOURCE,
            Request::METHOD_GET,
            [],
            [],
            [],
            $this->headers
        );

        $response = $this->createGlueControllerListenerPlugin()
            ->filter(
                new TestsResourceController(),
                'getAction',
                $request
            );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testFilterPostShouldSubmitAndReturnPostData(): void
    {
        $request = Request::create(
            static::URL_TEST_RESOURCE,
            Request::METHOD_POST,
            [],
            [],
            [],
            $this->headers,
            self::TESTS_POST_DATA
        );

        $request->attributes->add($this->attributes);

        $response = $this->createGlueControllerListenerPlugin()
            ->filter(
                new TestsResourceController(),
                'postAction',
                $request
            );

        $content = json_decode($response->getContent(), true);

        $data = $content[RestResponseInterface::RESPONSE_DATA];
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('1', $data[RestResourceInterface::RESOURCE_ID]);
        $this->assertEquals('tests', $data[RestResourceInterface::RESOURCE_TYPE]);
        $this->assertCount(2, $data[RestResourceInterface::RESOURCE_ATTRIBUTES]);
    }

    /**
     * @return void
     */
    public function testFilterPostWhenBusinessValidationFailsShouldReturnError(): void
    {
        $request = Request::create(
            static::URL_TEST_RESOURCE,
            Request::METHOD_POST,
            [],
            [],
            [],
            $this->headers,
            '{"data":{"type":"tests","attributes":{}}}'
        );

        $request->attributes->add($this->attributes);

        $response = $this->createGlueControllerListenerPlugin()
            ->filter(
                new TestsResourceController(),
                'postAction',
                $request
            );

        $content = json_decode($response->getContent(), true);

        $errors = $content[RestResponseInterface::RESPONSE_ERRORS];
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertCount(1, $errors);

        $this->assertEquals(1, $errors[0]['code']);
        $this->assertEquals('Invalid data', $errors[0]['detail']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $errors[0]['status']);
    }

    /**
     * @return void
     */
    public function testFilterDeleteShouldReturnCorrectStatusCode(): void
    {
        $request = Request::create(
            static::URL_TEST_RESOURCE,
            Request::METHOD_DELETE,
            [],
            [],
            [],
            $this->headers
        );

        $request->attributes->add($this->attributes);

        $response = $this->createGlueControllerListenerPlugin()
            ->filter(
                new TestsResourceController(),
                'deleteAction',
                $request
            );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testFilterPatchShouldReturnCorrectStatusCode(): void
    {
        $request = Request::create(
            static::URL_TEST_RESOURCE,
            Request::METHOD_PATCH,
            [],
            [],
            [],
            $this->headers,
            self::TESTS_POST_DATA
        );

        $request->attributes->add($this->attributes);

        $response = $this->createGlueControllerListenerPlugin()
            ->filter(
                new TestsResourceController(),
                'patchAction',
                $request
            );

        $content = json_decode($response->getContent(), true);
        $data = $content[RestResponseInterface::RESPONSE_DATA];
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('1', $data[RestResourceInterface::RESOURCE_ID]);
        $this->assertEquals('tests', $data[RestResourceInterface::RESOURCE_TYPE]);
        $this->assertCount(2, $data[RestResourceInterface::RESOURCE_ATTRIBUTES]);
    }

    /**
     * @return void
     */
    public function testFilterGetPagination(): void
    {
        $request = Request::create(
            static::URL_TEST_RESOURCE,
            Request::METHOD_GET,
            [
                'page' => [
                    'offset' => 0,
                    'limit' => 2,
                ],
            ],
            [],
            [],
            $this->headers
        );

        $request->attributes->add($this->attributes);

        $response = $this->createGlueControllerListenerPlugin()
            ->filter(
                new TestsResourceController(),
                'getAction',
                $request
            );

        $content = json_decode($response->getContent(), true);

        $this->assertPaginationKeys($content);
        $this->assertUri($content, 'first', 2, 0);
        $this->assertUri($content, 'last', 2, 18);
        $this->assertUri($content, 'next', 2, 2);
        $this->assertUri($content, 'prev', 2, 0);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Plugin\Rest\GlueControllerListenerPlugin
     */
    protected function createGlueControllerListenerPlugin(): GlueControllerListenerPlugin
    {
        return new GlueControllerListenerPlugin();
    }

    /**
     * @param array $content
     *
     * @return void
     */
    protected function assertPaginationKeys(array $content): void
    {
        $this->assertArrayHasKey(RestResponseInterface::RESPONSE_LINKS, $content);
        $this->assertArrayHasKey('first', $content[RestResponseInterface::RESPONSE_LINKS]);
        $this->assertArrayHasKey('last', $content[RestResponseInterface::RESPONSE_LINKS]);
        $this->assertArrayHasKey('next', $content[RestResponseInterface::RESPONSE_LINKS]);
        $this->assertArrayHasKey('prev', $content[RestResponseInterface::RESPONSE_LINKS]);
    }

    /**
     * @param array $content
     * @param string $field
     * @param int $limit
     * @param int $offset
     *
     * @return void
     */
    protected function assertUri(array $content, string $field, int $limit, int $offset): void
    {
        $queryParts = [];

        $link = parse_url($content[RestResponseInterface::RESPONSE_LINKS][$field]);
        parse_str($link['query'], $queryParts);
        $this->assertEquals($limit, $queryParts['page']['limit']);
        $this->assertEquals($offset, $queryParts['page']['offset']);
    }
}
