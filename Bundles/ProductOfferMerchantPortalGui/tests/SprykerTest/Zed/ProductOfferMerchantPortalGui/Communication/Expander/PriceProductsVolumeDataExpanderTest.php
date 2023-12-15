<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferMerchantPortalGui\Communication\Expander;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use SprykerTest\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferMerchantPortalGui
 * @group Communication
 * @group Expander
 * @group PriceProductsVolumeDataExpanderTest
 * Add your own group annotations below this line
 */
class PriceProductsVolumeDataExpanderTest extends Unit
{
    /**
     * @var array<string, int>
     */
    protected const REQUEST_DATA = [
        'original[moneyValue][grossAmount]' => 90,
    ];

    /**
     * @var \SprykerTest\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiCommunicationTester
     */
    protected ProductOfferMerchantPortalGuiCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandPriceProductsWithVolumeDataExpandsExistingVolumeData(): void
    {
        // Arrange
        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setFkCurrency(1)
            ->setFkStore(1)
            ->setPriceData('{"volume_prices":[{"quantity":4,"net_price":100,"gross_price":100}]}');
        $priceProductTransfer = (new PriceProductTransfer())
            ->setMoneyValue($moneyValueTransfer)
            ->setPriceType((new PriceTypeTransfer())->setIdPriceType(1));
        $priceProductOfferDataProviderMock = $this->tester->createPriceProductOfferDataProviderMock($priceProductTransfer);
        $productOfferMerchantPortalGuiCommunicationFactory = $this->tester
            ->createProductOfferMerchantPortalGuiCommunicationFactoryMock([
                'createPriceProductOfferDataProvider' => $priceProductOfferDataProviderMock,
            ]);
        $priceProductsVolumeDataExpander = $productOfferMerchantPortalGuiCommunicationFactory
            ->createPriceProductsVolumeDataExpander();

        // Act
        $priceProductTransfers = $priceProductsVolumeDataExpander->expandPriceProductsWithVolumeData(
            new ArrayObject([$priceProductTransfer]),
            static::REQUEST_DATA,
            7,
            1,
        );

        // Assert
        $this->assertArrayHasKey(0, $priceProductTransfers);
        $this->assertSame(
            '{"volume_prices":[{"quantity":4,"net_price":100,"gross_price":100},{"quantity":7,"net_price":null,"gross_price":9000}]}',
            $priceProductTransfers[0]->getMoneyValueOrFail()->getPriceData(),
        );
    }

    /**
     * @return void
     */
    public function testExpandPriceProductsWithVolumeDataAddsNewVolumeData(): void
    {
        // Arrange
        $priceProductTransfer = (new PriceProductTransfer())
            ->setMoneyValue((new MoneyValueTransfer())
                ->setFkCurrency(1)
                ->setFkStore(1))->setPriceType((new PriceTypeTransfer())->setIdPriceType(1));
        $priceProductOfferDataProviderMock = $this->tester->createPriceProductOfferDataProviderMock($priceProductTransfer);
        $productOfferMerchantPortalGuiCommunicationFactory = $this->tester
            ->createProductOfferMerchantPortalGuiCommunicationFactoryMock([
                'createPriceProductOfferDataProvider' => $priceProductOfferDataProviderMock,
            ]);
        $priceProductsVolumeDataExpander = $productOfferMerchantPortalGuiCommunicationFactory
            ->createPriceProductsVolumeDataExpander();

        // Act
        $priceProductTransfers = $priceProductsVolumeDataExpander->expandPriceProductsWithVolumeData(
            new ArrayObject([$priceProductTransfer]),
            static::REQUEST_DATA,
            7,
            1,
        );

        // Assert
        $this->assertArrayHasKey(0, $priceProductTransfers);
        $this->assertSame(
            '{"volume_prices":[{"quantity":7,"net_price":null,"gross_price":9000}]}',
            $priceProductTransfers[0]->getMoneyValueOrFail()->getPriceData(),
        );
    }
}
