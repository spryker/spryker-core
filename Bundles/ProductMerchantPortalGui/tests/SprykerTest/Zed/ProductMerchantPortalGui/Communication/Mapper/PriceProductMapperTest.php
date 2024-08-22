<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMerchantPortalGui\Communication\Mapper;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use SprykerTest\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMerchantPortalGui
 * @group Communication
 * @group Mapper
 * @group PriceProductMapperTest
 * Add your own group annotations below this line
 */
class PriceProductMapperTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiCommunicationTester
     */
    protected ProductMerchantPortalGuiCommunicationTester $tester;

    /**
     * @var string
     */
    protected const ID_PRODUCT_ABSTRACT = '999';

    /**
     * @return void
     */
    public function testMapRequestDataToPriceProductCriteriaTransferMapsJsonValues(): void
    {
        // Arrange
        $priceProductCriteriaTransfer = new PriceProductCriteriaTransfer();
        $requestQueryParams = [
            PriceProductTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS => '["111", "333"]',
        ];

        // Act
        $priceProductCriteriaTransfer = $this->tester->createPriceProductMapper()->mapRequestDataToPriceProductCriteriaTransfer(
            $requestQueryParams,
            $priceProductCriteriaTransfer,
        );

        // Assert
        $this->assertSame(['111', '333'], $priceProductCriteriaTransfer->getPriceProductStoreIds());
    }

    /**
     * @return void
     */
    public function testMapRequestDataToPriceProductCriteriaTransferMapsScalarValues(): void
    {
        // Arrange
        $priceProductCriteriaTransfer = new PriceProductCriteriaTransfer();
        $requestQueryParams = [
            PriceProductTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS => '[]',
            PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT => static::ID_PRODUCT_ABSTRACT,
        ];

        $priceProductMapper = $this->tester->createPriceProductMapper([
            $this->tester->createSetIdProductAbstractPriceProductMapperPluginMock(),
        ]);

        // Act
        $priceProductCriteriaTransfer = $priceProductMapper->mapRequestDataToPriceProductCriteriaTransfer(
            $requestQueryParams,
            $priceProductCriteriaTransfer,
        );

        // Assert
        $this->assertSame((int)static::ID_PRODUCT_ABSTRACT, $priceProductCriteriaTransfer->getIdProductAbstract());
        $this->assertSame([], $priceProductCriteriaTransfer->getPriceProductStoreIds());
    }

    /**
     * @return void
     */
    public function testMapTableRowsToPriceProductTransfersProcessesNewPriceProductsWithMissingCurrencyOrStoreInformation(): void
    {
        // Arrange
        $newPriceProducts = [
            [
                'store' => '',
                'currency' => '',
                'default[moneyValue][netAmount]' => '100',
                'default[moneyValue][grossAmount]' => '120',
                'original[moneyValue][netAmount]' => '130',
                'original[moneyValue][grossAmount]' => '150',
                'volumeQuantity' => '1',
            ],
        ];
        $priceProductTransfers = new ArrayObject();
        $priceProductMapper = $this->tester->createPriceProductMapper();

        // Act
        $result = $priceProductMapper->mapTableRowsToPriceProductTransfers($newPriceProducts, $priceProductTransfers);

        // Assert
        $this->tester->assertCount(1, $result);
    }
}
