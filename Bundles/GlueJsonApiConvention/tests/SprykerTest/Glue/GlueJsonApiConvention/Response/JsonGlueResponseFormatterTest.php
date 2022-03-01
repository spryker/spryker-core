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
use Spryker\Glue\GlueJsonApiConvention\Response\JsonGlueResponseFormatter;
use Spryker\Glue\GlueJsonApiConvention\Response\ResponseSparseFieldFormatter;
use Spryker\Glue\GlueJsonApiConvention\Response\ResponseSparseFieldFormatterInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Response
 * @group JsonGlueResponseFormatterTest
 *
 * Add your own group annotations below this line
 */
class JsonGlueResponseFormatterTest extends Unit
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
    public function testFormatResponseData(): void
    {
        //Act
        $jsonGlueResponseFormatter = new JsonGlueResponseFormatter(
            $this->createJsonEncoder(),
            $this->getJsonApiConventionConfigMock(),
            $this->createResponseSparseFieldFormatter(),
        );
        $formatedResponseData = $jsonGlueResponseFormatter->formatResponseData(
            $this->getGlueResourcesTestData(),
            $this->getSparseFieldsTestData(),
            (new GlueRequestTransfer())->setResource((new GlueResourceTransfer())),
        );

        //Assert
        $this->assertNotNull($formatedResponseData);
        $this->assertIsString($formatedResponseData);
        $this->assertStringContainsString('articles', $formatedResponseData);
        $decodedData = json_decode($formatedResponseData, true);
        $this->assertArrayHasKey('type', $decodedData['data'][0]);
        $this->assertSame('articles', $decodedData['data'][0]['type']);
        $this->assertArrayHasKey('links', $decodedData['data'][0]);
        $this->assertArrayHasKey('id', $decodedData['data'][0]);
    }

    /**
     * @return void
     */
    public function testFormatResponseDataWithExcludeRelationships(): void
    {
        //Act
        $jsonGlueResponseFormatter = new JsonGlueResponseFormatter(
            $this->createJsonEncoder(),
            $this->getJsonApiConventionConfigMock(),
            $this->createResponseSparseFieldFormatter(),
        );
        $formatedResponseData = $jsonGlueResponseFormatter->formatResponseData(
            $this->getGlueResourcesTestData(),
            $this->getSparseFieldsTestData(),
            (new GlueRequestTransfer())->setResource((new GlueResourceTransfer())),
        );

        //Assert
        $this->assertNotNull($formatedResponseData);
        $this->assertIsString($formatedResponseData);
        $decodedData = json_decode($formatedResponseData, true);
        $this->assertArrayHasKey('type', $decodedData['data'][0]);
        $this->assertArrayHasKey('id', $decodedData['data'][0]);
        $this->assertSame('1', $decodedData['data'][0]['id']);
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Response\ResponseSparseFieldFormatterInterface
     */
    protected function createResponseSparseFieldFormatter(): ResponseSparseFieldFormatterInterface
    {
        return new ResponseSparseFieldFormatter();
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
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function getGlueResourcesTestData(): GlueResponseTransfer
    {
        $links = new ArrayObject();
        $links['self'] = 'http://example.com/articles/1';

        $resourceTransfer = (new GlueResourceTransfer())
            ->setType('articles')
            ->setId('1')
            ->setLinks($links);

        $glueResponseTransfer = (new GlueResponseTransfer())
            ->addResource($resourceTransfer);

        return $glueResponseTransfer;
    }

    /**
     * @return array
     */
    protected function getSparseFieldsTestData(): array
    {
        return [
            'test-type' => ['id', 'type'],
        ];
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
