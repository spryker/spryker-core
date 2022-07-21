<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMerchantPortalGui\Communication\Mapper;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMerchantPortalGui
 * @group Communication
 * @group Mapper
 * @group SingleFieldPriceProductMapperTest
 * Add your own group annotations below this line
 */
class SingleFieldPriceProductMapperTest extends Unit
{
    /**
     * @var int
     */
    protected const PRICE_VALUE_1 = 1234;

    /**
     * @var int
     */
    protected const PRICE_VALUE_2 = 5678;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory
     */
    protected $productMerchantPortalGuiCommunicationFactory;

    /**
     * @var \SprykerTest\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->productMerchantPortalGuiCommunicationFactory = $this->tester->createProductMerchantPortalGuiCommunicationFactoryMock();
    }

    /**
     * @return void
     */
    public function testMapPriceProductTransfersReturnsCorrectResponseAfterStoreEdit(): void
    {
        // Arrange
        $priceProductTransfer = $this->tester->createFakePriceProductTransfer(static::PRICE_VALUE_1);

        // Act
        $mappedPriceProductTransfers = $this->productMerchantPortalGuiCommunicationFactory
            ->createSingleFieldPriceProductMapper()
            ->mapPriceProductTransfers(
                ['store' => $this->tester::FAKE_STORE_ID_2],
                1,
                new ArrayObject([clone $priceProductTransfer]),
            );

        // Assert
        $this->assertPriceProductDefaultIdIsNotCopied($mappedPriceProductTransfers, $priceProductTransfer);
    }

    /**
     * @return void
     */
    public function testMapPriceProductTransfersReturnsCorrectResponseAfterCurrencyEdit(): void
    {
        // Arrange
        $priceProductTransfer = $this->tester->createFakePriceProductTransfer(static::PRICE_VALUE_1);

        // Act
        $mappedPriceProductTransfers = $this->productMerchantPortalGuiCommunicationFactory
            ->createSingleFieldPriceProductMapper()
            ->mapPriceProductTransfers(
                ['currency' => $this->tester::FAKE_CURRENCY_ID_2],
                1,
                new ArrayObject([clone $priceProductTransfer]),
            );

        // Assert
        $this->assertPriceProductDefaultIdIsNotCopied($mappedPriceProductTransfers, $priceProductTransfer);
    }

    /**
     * @return void
     */
    public function testMapPriceProductTransfersReturnsCorrectResponseAfterPriceAdded(): void
    {
        // Arrange
        $priceProductTransfer = $this->tester->createFakePriceProductTransfer(static::PRICE_VALUE_1);

        // Act
        $mappedPriceProductTransfers = $this->productMerchantPortalGuiCommunicationFactory
            ->createSingleFieldPriceProductMapper()
            ->mapPriceProductTransfers(
                [$this->tester::FAKE_PRICE_TYPE_2 . '[moneyValue][netAmount]' => static::PRICE_VALUE_2],
                1,
                new ArrayObject([clone $priceProductTransfer]),
            );

        // Assert
        $this->assertPriceProductDefaultIdIsNotCopied($mappedPriceProductTransfers, $priceProductTransfer);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $mappedPriceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    protected function assertPriceProductDefaultIdIsNotCopied(
        ArrayObject $mappedPriceProductTransfers,
        PriceProductTransfer $priceProductTransfer
    ): void {
        foreach ($mappedPriceProductTransfers as $mappedPriceProductTransfer) {
            $this->verifyMappedPriceProductTransfer($priceProductTransfer, $mappedPriceProductTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $mappedPriceProductTransfer
     *
     * @return void
     */
    protected function verifyMappedPriceProductTransfer(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTransfer $mappedPriceProductTransfer
    ): void {
        if (
            $mappedPriceProductTransfer->getIdPriceProduct() !== null
            && $mappedPriceProductTransfer->getIdPriceProduct() === $priceProductTransfer->getIdPriceProduct()
        ) {
            $this->assertSame(
                $priceProductTransfer->getPriceDimension()->getIdPriceProductDefault(),
                $mappedPriceProductTransfer->getPriceDimension()->getIdPriceProductDefault(),
            );

            return;
        }
        $this->assertNull(
            $mappedPriceProductTransfer->getPriceDimension()->getIdPriceProductDefault(),
        );
    }
}
