<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Response;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceBridge;
use Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceInterface;
use Spryker\Glue\GlueJsonApiConvention\Encoder\EncoderInterface;
use Spryker\Glue\GlueJsonApiConvention\Encoder\JsonEncoder;
use Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionConfig;
use Spryker\Glue\GlueJsonApiConvention\Response\JsonApiResponseBuilder;
use Spryker\Glue\GlueJsonApiConvention\Response\JsonGlueResponseFormatter;
use Spryker\Glue\GlueJsonApiConvention\Response\JsonGlueResponseFormatterInterface;
use Spryker\Glue\GlueJsonApiConvention\Response\ResponseSparseFieldFormatter;
use Spryker\Glue\GlueJsonApiConvention\Response\ResponseSparseFieldFormatterInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Response
 * @group JsonApiResponseBuilderTest
 *
 * Add your own group annotations below this line
 */
class JsonApiResponseBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const GLUE_DOMAIN = 'GLUE_STOREFRONT_API_APPLICATION:GLUE_STOREFRONT_API_HOST';

    /**
     * @var \SprykerTest\Glue\GlueJsonApiConvention\GlueJsonApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBuildResponseData(): void
    {
        //Act
        $jsonApiResponseBuilder = new JsonApiResponseBuilder($this->createJsonGlueResponseFormatter());
        $buildResponse = $jsonApiResponseBuilder->buildResponse(
            $this->getGlueResponseTransfer(),
            $this->getGlueRequestTransfer(),
        );

        //Assert
        $content = $buildResponse->getContent();
        $this->assertNotNull($content);
        $this->assertIsString($content);
        $this->assertStringContainsString('articles', $content);

        $decodedContent = json_decode($content, true);
        $this->assertIsArray($decodedContent);
        $this->assertArrayHasKey('data', $decodedContent);
        $this->assertArrayHasKey('id', $decodedContent['data']);
        $this->assertSame('1', $decodedContent['data']['id']);
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): GlueJsonApiConventionToUtilEncodingServiceInterface
    {
        return new GlueJsonApiConventionToUtilEncodingServiceBridge(
            $this->tester->getLocator()->utilEncoding()->service(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Encoder\EncoderInterface
     */
    protected function createJsonEncoder(): EncoderInterface
    {
        return new JsonEncoder($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Response\JsonGlueResponseFormatterInterface
     */
    protected function createJsonGlueResponseFormatter(): JsonGlueResponseFormatterInterface
    {
        return new JsonGlueResponseFormatter($this->createJsonEncoder(), $this->getJsonApiConventionConfigMock(), $this->createResponseSparseFieldFormatter());
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Response\ResponseSparseFieldFormatterInterface
     */
    protected function createResponseSparseFieldFormatter(): ResponseSparseFieldFormatterInterface
    {
        return new ResponseSparseFieldFormatter();
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function getGlueRequestTransfer(): GlueRequestTransfer
    {
        $includedRelationships = [];
        $includedRelationships['type'] = 'author';

        return (new GlueRequestTransfer())
            ->setIncludedRelationships($includedRelationships)
            ->setResource((new GlueResourceTransfer())
                ->setType('articles')
                ->setId('1'));
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function getGlueResponseTransfer(): GlueResponseTransfer
    {
        $links = new ArrayObject();
        $links['self'] = 'http://example.com/articles/1';

        return (new GlueResponseTransfer())
            ->addResource((new GlueResourceTransfer())
                ->setType('articles')
                ->setId('1')
                ->setLinks($links));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionConfig|mixed
     */
    protected function getJsonApiConventionConfigMock()
    {
        $configMock = $this->createMock(GlueJsonApiConventionConfig::class);
        $configMock->expects($this->never())
            ->method('getGlueDomain')
            ->willReturn(static::GLUE_DOMAIN);

        return $configMock;
    }
}
