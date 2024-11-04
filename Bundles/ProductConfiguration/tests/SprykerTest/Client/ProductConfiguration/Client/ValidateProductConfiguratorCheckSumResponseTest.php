<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfiguration\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCurrencyClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientInterface;
use Spryker\Client\ProductConfiguration\ProductConfigurationFactory;
use Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorResponseValidatorInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfiguration
 * @group Client
 * @group ValidateProductConfiguratorCheckSumResponseTest
 * Add your own group annotations below this line
 */
class ValidateProductConfiguratorCheckSumResponseTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfiguration\ProductConfigurationClientTester
     */
    protected $tester;

    /**
     * @var \Spryker\Client\ProductConfiguration\ProductConfigurationClientInterface
     */
    protected $productConfigurationClient;

    /**
     * @var \Spryker\Client\ProductConfiguration\ProductConfigurationFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productConfigurationFactoryMock;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->productConfigurationFactoryMock = $this->getProductConfigurationFactoryMock();

        $this->productConfigurationClient = $this->tester->getClient()->setFactory($this->productConfigurationFactoryMock);
    }

    /**
     * @return void
     */
    public function testWillSetIsSuccessTrueWhenValidationPass(): void
    {
        // Arrange
        $this->productConfigurationFactoryMock->method('createProductConfiguratorCheckSumResponseValidators')
            ->willReturn([]);

        $productConfiguratorResponseProcessorResponseTransfer = (new ProductConfiguratorResponseProcessorResponseTransfer())
            ->setIsSuccessful(true);

        // Act
        $productConfiguratorResponseProcessorResponseTransferValidated = $this->productConfigurationClient
            ->validateProductConfiguratorCheckSumResponse($productConfiguratorResponseProcessorResponseTransfer, []);

        // Assert
        $this->assertTrue(
            $productConfiguratorResponseProcessorResponseTransferValidated->getIsSuccessful(),
            'Expects valid processor response after checksum validators.',
        );
    }

    /**
     * @return void
     */
    public function testWillSetIsSuccessFalseWhenValidationFail(): void
    {
        // Arrange
        $validatorMock = $this->getMockBuilder(ProductConfiguratorResponseValidatorInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateProductConfiguratorCheckSumResponse'])
            ->getMock();

        $validatorMock->method('validateProductConfiguratorCheckSumResponse')->willReturn(
            (new ProductConfiguratorResponseProcessorResponseTransfer())->setIsSuccessful(false),
        );

        $this->productConfigurationFactoryMock->method('createProductConfiguratorCheckSumResponseValidators')
            ->willReturn([
                $validatorMock,
            ]);

        $productConfiguratorResponseProcessorResponseTransfer = (new ProductConfiguratorResponseProcessorResponseTransfer())
            ->setIsSuccessful(true);

        // Act
        $productConfiguratorResponseProcessorResponseTransferValidated = $this->productConfigurationClient
            ->validateProductConfiguratorCheckSumResponse($productConfiguratorResponseProcessorResponseTransfer, []);

        // Assert
        $this->assertFalse(
            $productConfiguratorResponseProcessorResponseTransferValidated->getIsSuccessful(),
            'Expects not valid processor response after checksum validator fail.',
        );
    }

    /**
     * @return void
     */
    public function testWillSetIsSuccessFalseAndReturnOnFirstFailedValidator(): void
    {
        // Arrange
        $validatorMockOne = $this->getMockBuilder(ProductConfiguratorResponseValidatorInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateProductConfiguratorCheckSumResponse'])
            ->getMock();

        $validatorMockOne->method('validateProductConfiguratorCheckSumResponse')->willReturn(
            (new ProductConfiguratorResponseProcessorResponseTransfer())->setIsSuccessful(false),
        );

        $validatorMockTwo = $this->getMockBuilder(ProductConfiguratorResponseValidatorInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateProductConfiguratorCheckSumResponse'])
            ->getMock();

        $validatorMockTwo->expects($this->never())->method('validateProductConfiguratorCheckSumResponse');

        $this->productConfigurationFactoryMock->method('createProductConfiguratorCheckSumResponseValidators')
            ->willReturn([
                $validatorMockOne,
                $validatorMockTwo,
            ]);

        $productConfiguratorResponseProcessorResponseTransfer = (new ProductConfiguratorResponseProcessorResponseTransfer())
            ->setIsSuccessful(true);

        // Act
        $productConfiguratorResponseProcessorResponseTransferValidated = $this->productConfigurationClient
            ->validateProductConfiguratorCheckSumResponse($productConfiguratorResponseProcessorResponseTransfer, []);

        // Assert
        $this->assertFalse(
            $productConfiguratorResponseProcessorResponseTransferValidated->getIsSuccessful(),
            'Expects that return not valid processor response after first checksum validator fail.',
        );
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\ProductConfigurationFactory
     */
    protected function getProductConfigurationFactoryMock(): ProductConfigurationFactory
    {
        $productConfigurationFactoryMock = $this->getMockBuilder(ProductConfigurationFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'createProductConfiguratorCheckSumResponseValidators',
                'getStoreClient',
                'getCurrencyClient',
            ])
            ->getMock();

        $storeClientMock = $this->getMockBuilder(ProductConfigurationToStoreClientInterface::class)
            ->onlyMethods(['getCurrentStore'])->getMockForAbstractClass();

        $storeClientMock->method('getCurrentStore')->willReturn(new StoreTransfer());

        $productConfigurationFactoryMock->method('getStoreClient')
            ->willReturn($storeClientMock);

        $currencyClientMock = $this->getMockBuilder(ProductConfigurationToCurrencyClientInterface::class)
            ->onlyMethods(['getCurrent'])->getMockForAbstractClass();

        $currencyClientMock->method('getCurrent')->willReturn(new CurrencyTransfer());

        $productConfigurationFactoryMock->method('getCurrencyClient')
            ->willReturn($currencyClientMock);

        return $productConfigurationFactoryMock;
    }
}
