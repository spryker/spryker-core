<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\ProductConfiguration;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Service\ProductConfiguration\ProductConfigurationService;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group ProductConfiguration
 * @group ProductConfigurationServiceTest
 * Add your own group annotations below this line
 */
class ProductConfigurationServiceTest extends Unit
{
    /**
     * @return void
     */
    public function testGetProductConfigurationInstanceHashWillReturnSameHashForEqualProductConfigurationInstanceTransfers(): void
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
        $this->assertEquals($productConfigurationInstanceTransfer1Hash, $productConfigurationInstanceTransfer2Hash);
    }
}
