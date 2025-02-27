<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOffer\Communication\Plugin\CartReorder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Shared\ProductOffer\ProductOfferConfig;
use Spryker\Zed\ProductOffer\Communication\Plugin\CartReorder\RemoveInactiveProductOffersCartReorderPreAddToCartPlugin;
use SprykerTest\Zed\ProductOffer\ProductOfferCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOffer
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group RemoveInactiveProductOffersCartReorderPreAddToCartPluginTest
 * Add your own group annotations below this line
 */
class RemoveInactiveProductOffersCartReorderPreAddToCartPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOffer\ProductOfferCommunicationTester
     */
    protected ProductOfferCommunicationTester $tester;

    /**
     * @dataProvider throwsNullValueExceptionWhenRequiredCartChangePropertiesAreNotSetDataProvider
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param string $exceptionMessage
     *
     * @return void
     */
    public function testThrowsNullValueExceptionWhenRequiredCartChangePropertiesAreNotSet(
        CartChangeTransfer $cartChangeTransfer,
        string $exceptionMessage
    ): void {
        // Arrange
        $removeInactiveProductOffersCartReorderPreAddToCartPlugin = new RemoveInactiveProductOffersCartReorderPreAddToCartPlugin();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage($exceptionMessage);

        // Act
        $removeInactiveProductOffersCartReorderPreAddToCartPlugin->preAddToCart($cartChangeTransfer);
    }

    /**
     * @return void
     */
    public function testDoesNotFilterOutItemsWithoutProductOfferReference(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE'], false);
        $productTransfer = $this->tester->haveFullProduct();
        $productOfferTransfer1 = $this->haveProductOfferWithStore($productTransfer->getIdProductConcrete(), $storeTransfer, false);
        $productOfferTransfer2 = $this->haveProductOfferWithStore(
            $productTransfer->getIdProductConcrete(),
            $storeTransfer,
            false,
            ProductOfferConfig::STATUS_DENIED,
        );
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())->setSku($productOfferTransfer1->getConcreteSku())->setProductOfferReference(null))
            ->addItem((new ItemTransfer())->setSku($productOfferTransfer2->getConcreteSku())->setProductOfferReference(null))
            ->setQuote((new QuoteTransfer())->setStore((new StoreTransfer())->setName('DE')));
        $removeInactiveProductOffersCartReorderPreAddToCartPlugin = new RemoveInactiveProductOffersCartReorderPreAddToCartPlugin();

        // Act
        $cartChangeTransfer = $removeInactiveProductOffersCartReorderPreAddToCartPlugin->preAddToCart($cartChangeTransfer);

        // Assert
        $this->assertCount(2, $cartChangeTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testFiltersOutItemsWithNotActiveAndNotApprovedProductOffers(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE'], false);
        $productTransfer = $this->tester->haveFullProduct();
        $productOfferTransfer1 = $this->haveProductOfferWithStore($productTransfer->getIdProductConcrete(), $storeTransfer, false);
        $productOfferTransfer2 = $this->haveProductOfferWithStore($productTransfer->getIdProductConcrete(), $storeTransfer);
        $productOfferTransfer3 = $this->haveProductOfferWithStore(
            $productTransfer->getIdProductConcrete(),
            $storeTransfer,
            false,
            ProductOfferConfig::STATUS_DENIED,
        );
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())
                ->setSku($productOfferTransfer1->getConcreteSku())
                ->setProductOfferReference($productOfferTransfer1->getProductOfferReference()))
            ->addItem((new ItemTransfer())
                ->setSku($productOfferTransfer2->getConcreteSku())
                ->setProductOfferReference($productOfferTransfer2->getProductOfferReference()))
            ->addItem((new ItemTransfer())
                ->setSku($productOfferTransfer3->getConcreteSku())
                ->setProductOfferReference($productOfferTransfer3->getProductOfferReference()))
            ->setQuote((new QuoteTransfer())->setStore((new StoreTransfer())->setName('DE')));
        $removeInactiveProductOffersCartReorderPreAddToCartPlugin = new RemoveInactiveProductOffersCartReorderPreAddToCartPlugin();

        // Act
        $cartChangeTransfer = $removeInactiveProductOffersCartReorderPreAddToCartPlugin->preAddToCart($cartChangeTransfer);

        // Assert
        $this->assertCount(1, $cartChangeTransfer->getItems());
        $this->assertSame($productOfferTransfer2->getProductOfferReference(), $cartChangeTransfer->getItems()->getIterator()->current()->getProductOfferReference());
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param bool $isActive
     * @param string $approvalStatus
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function haveProductOfferWithStore(
        int $idProductConcrete,
        StoreTransfer $storeTransfer,
        bool $isActive = true,
        string $approvalStatus = ProductOfferConfig::STATUS_APPROVED
    ): ProductOfferTransfer {
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $idProductConcrete,
            ProductOfferTransfer::IS_ACTIVE => $isActive,
            ProductOfferTransfer::APPROVAL_STATUS => $approvalStatus,
        ]);
        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer);

        return $productOfferTransfer;
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\CartChangeTransfer|string>>
     */
    protected function throwsNullValueExceptionWhenRequiredCartChangePropertiesAreNotSetDataProvider(): array
    {
        return [
            'Quote is not provided' => [
                new CartChangeTransfer(),
                'Property "quote" of transfer `Generated\Shared\Transfer\CartChangeTransfer` is null.',
            ],
            'Quote store is not provided' => [
                (new CartChangeTransfer())->setQuote(new QuoteTransfer()),
                'Property "store" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.',
            ],
        ];
    }
}
