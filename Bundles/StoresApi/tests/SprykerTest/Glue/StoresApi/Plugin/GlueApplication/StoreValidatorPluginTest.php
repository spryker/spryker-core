<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\StoresApi\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreClientInterface;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreStorageClientInterface;
use Spryker\Glue\StoresApi\Processor\Validator\StoreValidator;
use Spryker\Glue\StoresApi\Processor\Validator\StoreValidatorInterface;
use Spryker\Glue\StoresApi\StoresApiConfig;
use Spryker\Glue\StoresApi\StoresApiFactory;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group StoresApi
 * @group Plugin
 * @group GlueApplication
 * @group StoreValidatorPluginTest
 * Add your own group annotations below this line
 */
class StoreValidatorPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const VALID_CURRENT_STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const INVALID_CURRENT_STORE_NAME = 'AA';

    /**
     * @var \SprykerTest\Glue\StoresApi\StoresApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateCallsStoreValidatorValidateMethod(): void
    {
        // Act
        $result = $this->validate(static::VALID_CURRENT_STORE_NAME, static::VALID_CURRENT_STORE_NAME);

        // Assert
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateReturnsInvalidValidationTransferWhenStoreIsInvalid(): void
    {
        // Act
        $result = $this->validate(static::VALID_CURRENT_STORE_NAME, static::INVALID_CURRENT_STORE_NAME);

        // Assert
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertFalse($result->getIsValid());
        $this->assertEquals(Response::HTTP_NOT_FOUND, $result->getStatus());
        $this->assertCount(1, $result->getErrors());
        $this->assertEquals(StoresApiConfig::RESPONSE_CODE_STORE_NOT_FOUND, $result->getErrors()[0]->getCode());
        $this->assertEquals(StoresApiConfig::GLOSSARY_KEY_VALIDATION_STORE_NOT_FOUND, $result->getErrors()[0]->getMessage());
    }

    /**
     * @param string $currentStoreName
     * @param string $storeName
     *
     * @return \Spryker\Glue\StoresApi\StoresApiFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockStoresApiFactory(
        string $currentStoreName,
        string $storeName,
    ): StoresApiFactory {
        $mockFactory = $this->createMock(StoresApiFactory::class);
        $mockFactory->method('createStoreRequestValidator')
            ->willReturn($this->createStoreValidator($currentStoreName, $storeName));

        return $mockFactory;
    }

    /**
     * @param string $currentStoreName
     *
     * @return \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockStoreClient(string $currentStoreName): StoresApiToStoreClientInterface
    {
        $storeClientMock = $this->createMock(StoresApiToStoreClientInterface::class);
        $storeClientMock->expects($this->once())
            ->method('getCurrentStore')
            ->willReturn((new StoreTransfer())->setName($currentStoreName));

        return $storeClientMock;
    }

    /**
     * @param string $currentStoreName
     * @param string $storeName
     *
     * @return \Spryker\Glue\StoresApi\Processor\Validator\StoreValidatorInterface
     */
    protected function createStoreValidator(
        string $currentStoreName,
        string $storeName,
    ): StoreValidatorInterface {
        return new StoreValidator(
            $this->getMockStoreClient($currentStoreName),
            $this->getMockStoreStorageClient($storeName),
        );
    }

    /**
     * @param string $storeName
     *
     * @return \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockStoreStorageClient(string $storeName): StoresApiToStoreStorageClientInterface
    {
        $storeStorageClientMock = $this->createMock(StoresApiToStoreStorageClientInterface::class);
        $storeStorageClientMock->expects($this->once())
            ->method('getStoreNames')
            ->willReturn([$storeName]);

        return $storeStorageClientMock;
    }

    /**
     * @param string $currentStoreName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function validate(
        string $currentStoreName,
        string $storeName,
    ): GlueRequestValidationTransfer {
        $mockFactory = $this->getMockStoresApiFactory($currentStoreName, $storeName);
        $storeValidatorPlugin = $this->tester->createStoreValidatorPlugin();
        $storeValidatorPlugin->setFactory($mockFactory);

        return $storeValidatorPlugin->validate($this->tester->createGlueRequestTransfer());
    }
}
