<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientBridge;
use Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider;
use Spryker\Glue\GlueApplication\Plugin\Rest\GlueControllerListenerPlugin;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestTestAttributesTransfer;
use SprykerTest\Glue\GlueApplication\Stub\TestsResourceController;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Plugin
 * @group GlueControllerFilterPluginTest
 * Add your own group annotations below this line
 */
class GlueControllerFilterPluginTest extends Unit
{
    use ContainerHelperTrait;

    /**
     * @var \SprykerTest\Glue\GlueApplication\GlueApplicationTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @return void
     *
     * @var string
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
     *
     * @var string
     */
    protected const TESTS_POST_DATA = '{"data":{"type":"tests","attributes":{"attribute1":"1", "attribute2": "2"}}}';

    /**
     * @return void
     */
    public function testFilterReturnsUnsupportedMediaTypeWhenUnsupportedMediaTypeProvided(): void
    {
        $request = Request::create(
            static::URL_TEST_RESOURCE,
            Request::METHOD_GET,
            [],
            [],
            [],
            [
                'HTTP_CONTENT-TYPE' => ' ',
            ],
        );

        $response = $this->createGlueControllerListenerPlugin()
            ->filter(
                new TestsResourceController(),
                'get',
                $request,
            );

        $this->assertSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $response->getStatusCode());
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
            $this->headers,
        );

        $this->mockStore();
        $response = $this->createGlueControllerListenerPlugin()
            ->filter(
                new TestsResourceController(),
                'getAction',
                $request,
            );

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
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
            static::TESTS_POST_DATA,
        );

        $request->attributes->add($this->attributes);

        $this->mockStore();

        $response = $this->createGlueControllerListenerPlugin()
            ->filter(
                new TestsResourceController(),
                'postAction',
                $request,
            );

        $content = json_decode($response->getContent(), true);

        $data = $content[RestResponseInterface::RESPONSE_DATA];
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertSame('1', $data[RestResourceInterface::RESOURCE_ID]);
        $this->assertSame('tests', $data[RestResourceInterface::RESOURCE_TYPE]);
        $this->assertCount(2, $data[RestResourceInterface::RESOURCE_ATTRIBUTES]);
    }

    /**
     * @return void
     */
    public function testFilterPostReturnsBadRequestWhenBusinessValidationFails(): void
    {
        $request = Request::create(
            static::URL_TEST_RESOURCE,
            Request::METHOD_POST,
            [],
            [],
            [],
            $this->headers,
            '{"data":{"type":"tests","attributes":{}}}',
        );

        $request->attributes->add($this->attributes);

        $this->mockStore();

        $response = $this->createGlueControllerListenerPlugin()
            ->filter(
                new TestsResourceController(),
                'postAction',
                $request,
            );

        $content = json_decode($response->getContent(), true);

        $errors = $content[RestResponseInterface::RESPONSE_ERRORS];
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertCount(1, $errors);

        $this->assertSame(1, $errors[0]['code']);
        $this->assertSame('Invalid data', $errors[0]['detail']);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $errors[0]['status']);
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
            $this->headers,
        );

        $request->attributes->add($this->attributes);

        $this->mockStore();

        $response = $this->createGlueControllerListenerPlugin()
            ->filter(
                new TestsResourceController(),
                'deleteAction',
                $request,
            );

        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
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
            static::TESTS_POST_DATA,
        );

        $request->attributes->add($this->attributes + [RequestConstantsInterface::ATTRIBUTE_ID => '1']);

        $this->mockStore();

        $response = $this->createGlueControllerListenerPlugin()
            ->filter(
                new TestsResourceController(),
                'patchAction',
                $request,
            );

        $content = json_decode($response->getContent(), true);
        $data = $content[RestResponseInterface::RESPONSE_DATA];
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('1', $data[RestResourceInterface::RESOURCE_ID]);
        $this->assertSame('tests', $data[RestResourceInterface::RESOURCE_TYPE]);
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
                    'offset' => 2,
                    'limit' => 2,
                ],
            ],
            [],
            [],
            $this->headers,
        );

        $request->attributes->add($this->attributes);

        $this->mockStore();

        $response = $this->createGlueControllerListenerPlugin()
            ->filter(
                new TestsResourceController(),
                'getAction',
                $request,
            );

        $content = json_decode($response->getContent(), true);

        $this->assertPaginationKeys($content);
        $this->assertUri($content, RestLinkInterface::LINK_FIRST, '2', '0');
        $this->assertUri($content, RestLinkInterface::LINK_LAST, '2', '18');
        $this->assertUri($content, RestLinkInterface::LINK_NEXT, '2', '4');
        $this->assertUri($content, RestLinkInterface::LINK_PREV, '2', '0');
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
        $this->assertArrayHasKey(RestLinkInterface::LINK_FIRST, $content[RestResponseInterface::RESPONSE_LINKS]);
        $this->assertArrayHasKey(RestLinkInterface::LINK_LAST, $content[RestResponseInterface::RESPONSE_LINKS]);
        $this->assertArrayHasKey(RestLinkInterface::LINK_NEXT, $content[RestResponseInterface::RESPONSE_LINKS]);
        $this->assertArrayHasKey(RestLinkInterface::LINK_PREV, $content[RestResponseInterface::RESPONSE_LINKS]);
    }

    /**
     * @param array $content
     * @param string $field
     * @param string $limit
     * @param string $offset
     *
     * @return void
     */
    protected function assertUri(array $content, string $field, string $limit, string $offset): void
    {
        $queryParts = [];

        $link = parse_url($content[RestResponseInterface::RESPONSE_LINKS][$field]);
        parse_str($link['query'], $queryParts);
        $this->assertSame($limit, $queryParts['page']['limit']);
        $this->assertSame($offset, $queryParts['page']['offset']);
    }

    /**
     * @return void
     */
    protected function mockStore(): void
    {
        $glueApplicationToStoreClientBridge = $this->createMock(GlueApplicationToStoreClientBridge::class);
        $storeTransfer = new StoreTransfer();
        $storeTransfer->setName('DE')
            ->setAvailableLocaleIsoCodes([static::LOCALE_DE]);
        $glueApplicationToStoreClientBridge
            ->method('getCurrentStore')
            ->willReturn($storeTransfer);

        $this->tester->setDependency(GlueApplicationDependencyProvider::CLIENT_STORE, $glueApplicationToStoreClientBridge);
    }
}
