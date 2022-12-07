<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\ProductConfiguration\ProductConfigurationService;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Service\ProductConfiguration\ProductConfigurationService;
use SprykerTest\Service\ProductConfiguration\ProductConfigurationServiceTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group ProductConfiguration
 * @group ProductConfigurationService
 * @group GetProductConfigurationInstanceHashTest
 * Add your own group annotations below this line
 */
class GetProductConfigurationInstanceHashTest extends Unit
{
    /**
     * @var \SprykerTest\Service\ProductConfiguration\ProductConfigurationServiceTester
     */
    protected ProductConfigurationServiceTester $tester;

    /**
     * @return void
     */
    public function testWillReturnSameHashForEqualProductConfigurationInstanceTransfers(): void
    {
        // Arrange
        $productConfigurationInstanceData = [
            ProductConfigurationInstanceTransfer::AVAILABLE_QUANTITY => 100,
            ProductConfigurationInstanceTransfer::CONFIGURATION => 'foo',
            ProductConfigurationInstanceTransfer::DISPLAY_DATA => 'bar',
            ProductConfigurationInstanceTransfer::PRICES => [
                [
                    PriceProductTransfer::SKU_PRODUCT => 'biz',
                    PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'baz',
                ],
            ],
        ];

        $productConfigurationInstanceTransfer1 = (new ProductConfigurationInstanceTransfer())->fromArray($productConfigurationInstanceData);
        $productConfigurationInstanceTransfer2 = (new ProductConfigurationInstanceTransfer())->fromArray($productConfigurationInstanceData);

        $productConfigurationService = new ProductConfigurationService();

        // Act
        $productConfigurationInstanceTransfer1Hash = $productConfigurationService->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer1);
        $productConfigurationInstanceTransfer2Hash = $productConfigurationService->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer2);

        // Assert
        $this->assertSame($productConfigurationInstanceTransfer1Hash, $productConfigurationInstanceTransfer2Hash);
    }

    /**
     * @return void
     */
    public function testWillReturnDifferentHashForDifferentProductConfigurationInstanceTransfers(): void
    {
        // Arrange
        $productConfigurationInstanceData1 = [
            ProductConfigurationInstanceTransfer::AVAILABLE_QUANTITY => 100,
            ProductConfigurationInstanceTransfer::CONFIGURATION => 'foo',
            ProductConfigurationInstanceTransfer::DISPLAY_DATA => 'bar',
        ];
        $productConfigurationInstanceData2 = [
            ProductConfigurationInstanceTransfer::AVAILABLE_QUANTITY => 200,
            ProductConfigurationInstanceTransfer::CONFIGURATION => 'biz',
            ProductConfigurationInstanceTransfer::DISPLAY_DATA => 'baz',
        ];

        $productConfigurationInstanceTransfer1 = (new ProductConfigurationInstanceTransfer())->fromArray($productConfigurationInstanceData1);
        $productConfigurationInstanceTransfer2 = (new ProductConfigurationInstanceTransfer())->fromArray($productConfigurationInstanceData2);

        $productConfigurationService = new ProductConfigurationService();

        // Act
        $productConfigurationInstanceTransfer1Hash = $productConfigurationService->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer1);
        $productConfigurationInstanceTransfer2Hash = $productConfigurationService->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer2);

        // Assert
        $this->assertNotSame($productConfigurationInstanceTransfer1Hash, $productConfigurationInstanceTransfer2Hash);
    }

    /**
     * @return void
     */
    public function testWillReturnSameHashForProductConfigurationInstanceTransfersWhenDisabledFieldDifferent(): void
    {
        // Arrange
        $productConfigurationInstanceData = [
            ProductConfigurationInstanceTransfer::CONFIGURATION => 'foo',
            ProductConfigurationInstanceTransfer::DISPLAY_DATA => 'bar',
            ProductConfigurationInstanceTransfer::PRICES => [
                [
                    PriceProductTransfer::SKU_PRODUCT => 'biz',
                    PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'baz',
                ],
            ],
            ProductConfigurationInstanceTransfer::CONFIGURATOR_KEY => 'foobar',
        ];

        $productConfigurationInstanceTransfer1 = (new ProductConfigurationInstanceTransfer())
            ->fromArray($productConfigurationInstanceData)
            ->setIsComplete(false);
        $productConfigurationInstanceTransfer2 = (new ProductConfigurationInstanceTransfer())
            ->fromArray($productConfigurationInstanceData)
            ->setIsComplete(true);

        /** @var \Spryker\Service\Kernel\AbstractBundleConfig $productConfigurationConfig */
        $productConfigurationConfig = $this->tester->mockConfigMethod('getConfigurationFieldsNotAllowedForEncoding', [
            ProductConfigurationInstanceTransfer::IS_COMPLETE,
        ]);

        $productConfigurationServiceFactory = $this->tester->getFactory();
        $productConfigurationServiceFactory->setConfig($productConfigurationConfig);

        /** @var \Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface $productConfigurationService */
        $productConfigurationService = $this->tester->getService()->setFactory($productConfigurationServiceFactory);

        // Act
        $productConfigurationInstanceTransfer1Hash = $productConfigurationService->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer1);
        $productConfigurationInstanceTransfer2Hash = $productConfigurationService->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer2);

        // Assert
        $this->assertSame($productConfigurationInstanceTransfer1Hash, $productConfigurationInstanceTransfer2Hash);
    }
}
