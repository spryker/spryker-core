<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Request;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\RequestFormatter;
use Spryker\Glue\GlueApplication\Rest\Request\RequestFormatterInterface;
use Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractorInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcherInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Request
 * @group RequestFormatterTest
 *
 * Add your own group annotations below this line
 */
class RequestFormatterTest extends Unit
{
    /**
     * @return void
     */
    public function testFormatRequestShouldSetRestRequest(): void
    {
        $requestFormatter = $this->createRequestFormatter(new RestResourceBuilder());

        $request = Request::create(
            '/',
            Request::METHOD_GET,
            [
                'fields' => [
                    'items' => 'att1,att2,att3',
                ],
                'filter' => [
                    'items.name' => 'item name',
                ],
                'page' => [
                    'limit' => 1,
                    'offset' => 10,
                ],
                'include' => 'resource1,resource2',
                'sort' => 'attr1,-attr2',
            ],
            [],
            [],
            [
                'HTTP_CONTENT-TYPE' => 'application/vnd.api+json; version=1.0',
                'HTTP_ACCEPT' => 'application/vnd.api+json; version=1.0',
                'Accept-Language' => 'en; de;q=0.5',
            ]
        );

        $request->attributes->add([
            RequestConstantsInterface::ATTRIBUTE_TYPE => 'tests',
            RequestConstantsInterface::ATTRIBUTE_ID => 1,
        ]);

        $restRequest = $requestFormatter->formatRequest($request);

        $this->assertCount(1, $restRequest->getFields());
        $this->assertCount(3, $restRequest->getFields()['items']->getAttributes());
        $this->assertEquals('items', $restRequest->getFields()['items']->getResource());
        $this->assertTrue($restRequest->hasField('items'));
        $this->assertCount(3, $restRequest->getField('items')->getAttributes());
        $this->assertEquals('items', $restRequest->getField('items')->getResource());

        $this->assertCount(1, $restRequest->getFilters());
        $this->assertEquals('name', $restRequest->getFilters()['items'][0]->getField());
        $this->assertEquals('items', $restRequest->getFilters()['items'][0]->getResource());
        $this->assertEquals('item name', $restRequest->getFilters()['items'][0]->getValue());

        $this->assertTrue($restRequest->hasFilters('items'));
        $this->assertCount(1, $restRequest->getFiltersByResource('items'));

        $this->assertEquals('json', $restRequest->getMetadata()->getAcceptFormat());
        $this->assertEquals('json', $restRequest->getMetadata()->getContentTypeFormat());
        $this->assertEquals('DE', $restRequest->getMetadata()->getLocale());
        $this->assertEquals('GET', $restRequest->getMetadata()->getMethod());
        $this->assertEquals(1, $restRequest->getMetadata()->getVersion()->getMajor());
        $this->assertEquals(1, $restRequest->getMetadata()->getVersion()->getMinor());

        $this->assertEquals(1, $restRequest->getPage()->getLimit());
        $this->assertEquals(10, $restRequest->getPage()->getOffset());

        $this->assertCount(2, $restRequest->getSort());
        $this->assertEquals('attr1', $restRequest->getSort()[0]->getField());
        $this->assertEquals('ASC', $restRequest->getSort()[0]->getDirection());
        $this->assertEquals('attr2', $restRequest->getSort()[1]->getField());
        $this->assertEquals('DESC', $restRequest->getSort()[1]->getDirection());

        $this->assertCount(2, $restRequest->getInclude());
        $this->assertArrayHasKey('resource1', $restRequest->getInclude());
        $this->assertArrayHasKey('resource2', $restRequest->getInclude());

        $this->assertEquals('tests', $restRequest->getResource()->getType());
        $this->assertEquals(1, $restRequest->getResource()->getId());
    }

    /**
     * @return void
     */
    public function testFormatRequestWhenIncludeEmptyShouldExcludeRel(): void
    {
        $requestFormatter = $this->createRequestFormatter(new RestResourceBuilder());

        $request = Request::create(
            '/',
            Request::METHOD_GET,
            [
                'include' => '',
            ],
            [],
            [],
            [
                'HTTP_CONTENT-TYPE' => 'application/vnd.api+json; version=1.0',
                'HTTP_ACCEPT' => 'application/vnd.api+json; version=1.0',
                'Accept-Language' => 'en; de;q=0.5',
            ]
        );

        $restRequest = $requestFormatter->formatRequest($request);

        $this->assertTrue($restRequest->getExcludeRelationship());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilderMock
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestFormatterInterface
     */
    protected function createRequestFormatter(
        RestResourceBuilderInterface $restResourceBuilderMock
    ): RequestFormatterInterface {
        return new RequestFormatter(
            $this->createRequestMetaDataExtractorMock(),
            $this->createDecoderMatcherMock(),
            $restResourceBuilderMock
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractorInterface
     */
    protected function createRequestMetaDataExtractorMock(): RequestMetaDataExtractorInterface
    {
        $requestMetaDataExtractorMock = $this->getMockBuilder(RequestMetaDataExtractorInterface::class)->getMock();

        $requestMetaDataExtractorMock
            ->method('extract')
            ->willReturn((new RestRequest)->createMetadata());

        return $requestMetaDataExtractorMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcherInterface
     */
    protected function createDecoderMatcherMock(): DecoderMatcherInterface
    {
        return $this->getMockBuilder(DecoderMatcherInterface::class)->getMock();
    }
}
