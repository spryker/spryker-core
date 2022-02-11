<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductApproval\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Shared\ProductApproval\ProductApprovalConfig;
use Spryker\Shared\ProductApproval\ProductApprovalConfig as ProductApprovalSharedConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductApproval
 * @group Business
 * @group Facade
 * @group ProductApprovalFacadeTest
 * Add your own group annotations below this line
 */
class ProductApprovalFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_1_SKU = 'YTP1yWAMap';

    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_2_SKU = 'tESnx2djdn';

    /**
     * @var \SprykerTest\Zed\ProductApproval\ProductApprovalBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetApplicableApprovalStatusesReturnsCorrectStatusesForWaitingForApproval(): void
    {
        // Act
        $applicableApprovalStatuses = $this->tester->getFacade()->getApplicableApprovalStatuses(
            ProductApprovalConfig::STATUS_WAITING_FOR_APPROVAL,
        );

        // Assert
        $this->assertSame(
            [
                ProductApprovalSharedConfig::STATUS_APPROVED,
                ProductApprovalSharedConfig::STATUS_DENIED,
                ProductApprovalSharedConfig::STATUS_DRAFT,
            ],
            $applicableApprovalStatuses,
        );
    }

    /**
     * @return void
     */
    public function testGetApplicableApprovalStatusesReturnsCorrectStatusesForApproved(): void
    {
        // Act
        $applicableApprovalStatuses = $this->tester->getFacade()->getApplicableApprovalStatuses(
            ProductApprovalConfig::STATUS_APPROVED,
        );

        // Assert
        $this->assertSame(
            [ProductApprovalSharedConfig::STATUS_DENIED, ProductApprovalSharedConfig::STATUS_DRAFT],
            $applicableApprovalStatuses,
        );
    }

    /**
     * @return void
     */
    public function testGetApplicableApprovalStatusesReturnsCorrectStatusesForDenied(): void
    {
        // Act
        $applicableApprovalStatuses = $this->tester->getFacade()->getApplicableApprovalStatuses(
            ProductApprovalConfig::STATUS_DENIED,
        );

        // Assert
        $this->assertSame(
            [
                ProductApprovalSharedConfig::STATUS_APPROVED,
                ProductApprovalSharedConfig::STATUS_DRAFT,
            ],
            $applicableApprovalStatuses,
        );
    }

    /**
     * @return void
     */
    public function testGetApplicableApprovalStatusesReturnsCorrectStatusesForDraft(): void
    {
        // Act
        $applicableApprovalStatuses = $this->tester->getFacade()->getApplicableApprovalStatuses(
            ProductApprovalConfig::STATUS_DRAFT,
        );

        // Assert
        $this->assertSame(
            [
                ProductApprovalSharedConfig::STATUS_APPROVED,
                ProductApprovalSharedConfig::STATUS_WAITING_FOR_APPROVAL,
                ProductApprovalSharedConfig::STATUS_DENIED,
            ],
            $applicableApprovalStatuses,
        );
    }

    /**
     * @return void
     */
    public function testGetApplicableApprovalStatusesReturnsEmptyArrayForNotExistingApprovalStatus(): void
    {
        // Act
        $applicableApprovalStatuses = $this->tester->getFacade()->getApplicableApprovalStatuses('status_not_exist');

        // Assert
        $this->assertEmpty($applicableApprovalStatuses);
    }

    /**
     * @dataProvider getNotApprovedApprovalStatuses
     *
     * @param string|null $approvalStatus
     *
     * @return void
     */
    public function testFilterProductAbstractStorageCollectionFiltersOutNotApprovedProducts(?string $approvalStatus): void
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_1_SKU,
        ]);
        $productAbstractTransfer2 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => $approvalStatus,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_2_SKU,
        ]);
        $productAbstractStorageTransfers = [
            (new ProductAbstractStorageTransfer())
                ->setIdProductAbstract($productAbstractTransfer1->getIdProductAbstractOrFail())
                ->setSku($productAbstractTransfer1->getSkuOrFail()),
            (new ProductAbstractStorageTransfer())
                ->setIdProductAbstract($productAbstractTransfer2->getIdProductAbstractOrFail())
                ->setSku($productAbstractTransfer2->getSkuOrFail()),
        ];

        // Act
        $filteredProductAbstractStorageTransfers = $this->tester
            ->getFacade()
            ->filterProductAbstractStorageCollection($productAbstractStorageTransfers);

        // Assert
        $this->assertCount(1, $filteredProductAbstractStorageTransfers);
        $this->assertEquals(
            $productAbstractTransfer1->getIdProductAbstractOrFail(),
            $filteredProductAbstractStorageTransfers[0]->getIdProductAbstractOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testFilterProductAbstractStorageCollectionDoesNotFilterOutApprovedProducts(): void
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_1_SKU,
        ]);
        $productAbstractTransfer2 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_2_SKU,
        ]);
        $productAbstractStorageTransfers = [
            (new ProductAbstractStorageTransfer())
                ->setIdProductAbstract($productAbstractTransfer1->getIdProductAbstractOrFail())
                ->setSku($productAbstractTransfer1->getSkuOrFail()),
            (new ProductAbstractStorageTransfer())
                ->setIdProductAbstract($productAbstractTransfer2->getIdProductAbstractOrFail())
                ->setSku($productAbstractTransfer2->getSkuOrFail()),
        ];

        // Act
        $filteredProductAbstractStorageTransfers = $this->tester
            ->getFacade()
            ->filterProductAbstractStorageCollection($productAbstractStorageTransfers);

        // Assert
        $this->assertCount(2, $filteredProductAbstractStorageTransfers);
    }

    /**
     * @dataProvider getNotApprovedApprovalStatuses
     *
     * @param string|null $approvalStatus
     *
     * @return void
     */
    public function testFilterProductConcreteStorageCollectionFiltersOutNotApprovedProducts(?string $approvalStatus): void
    {
        // Arrange
        $productConcreteTransfer1 = $this->tester->haveFullProduct(
            [],
            [
                ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
                ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_1_SKU,
            ],
        );
        $productConcreteTransfer2 = $this->tester->haveFullProduct(
            [],
            [
                ProductAbstractTransfer::APPROVAL_STATUS => $approvalStatus,
                ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_2_SKU,
            ],
        );
        $productConcreteStorageTransfers = [
            (new ProductConcreteStorageTransfer())
                ->setIdProductConcrete($productConcreteTransfer1->getIdProductConcreteOrFail())
                ->setIdProductAbstract($productConcreteTransfer1->getFkProductAbstractOrFail())
                ->setSku($productConcreteTransfer1->getSkuOrFail()),
            (new ProductConcreteStorageTransfer())
                ->setIdProductConcrete($productConcreteTransfer2->getIdProductConcreteOrFail())
                ->setIdProductAbstract($productConcreteTransfer2->getFkProductAbstractOrFail())
                ->setSku($productConcreteTransfer2->getSkuOrFail()),
        ];

        // Act
        $filteredProductConcreteStorageTransfers = $this->tester
            ->getFacade()
            ->filterProductConcreteStorageCollection($productConcreteStorageTransfers);

        // Assert
        $this->assertCount(1, $filteredProductConcreteStorageTransfers);
        $this->assertEquals(
            $productConcreteTransfer1->getIdProductConcrete(),
            $filteredProductConcreteStorageTransfers[0]->getIdProductConcrete(),
        );
    }

    /**
     * @return void
     */
    public function testFilterProductConcreteStorageCollectionDoesNotFilterOutApprovedProducts(): void
    {
        // Arrange
        $productConcrete1Transfer = $this->tester->haveFullProduct(
            [],
            [
                ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
                ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_1_SKU,
            ],
        );
        $productConcrete2Transfer = $this->tester->haveFullProduct(
            [],
            [
                ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
                ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_2_SKU,
            ],
        );
        $productConcreteStorageTransfers = [
            (new ProductConcreteStorageTransfer())
                ->setIdProductConcrete($productConcrete1Transfer->getIdProductConcreteOrFail())
                ->setIdProductAbstract($productConcrete1Transfer->getFkProductAbstractOrFail())
                ->setSku($productConcrete1Transfer->getSkuOrFail()),
            (new ProductConcreteStorageTransfer())
                ->setIdProductConcrete($productConcrete2Transfer->getIdProductConcreteOrFail())
                ->setIdProductAbstract($productConcrete2Transfer->getFkProductAbstractOrFail())
                ->setSku($productConcrete2Transfer->getSkuOrFail()),
        ];

        // Act
        $filteredProductConcreteStorageTransfers = $this->tester
            ->getFacade()
            ->filterProductConcreteStorageCollection($productConcreteStorageTransfers);

        // Assert
        $this->assertCount(2, $filteredProductConcreteStorageTransfers);
    }

    /**
     * @dataProvider getNotApprovedApprovalStatuses
     *
     * @param string|null $approvalStatus
     *
     * @return void
     */
    public function testFilterProductPageSearchCollectionFiltersOutNotApprovedProducts(?string $approvalStatus): void
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_1_SKU,
        ]);
        $productAbstractTransfer2 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => $approvalStatus,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_2_SKU,
        ]);
        $productPageSearchTransfers = [
            (new ProductPageSearchTransfer())
                ->setIdProductAbstract($productAbstractTransfer1->getIdProductAbstractOrFail())
                ->setSku($productAbstractTransfer1->getSkuOrFail()),
            (new ProductPageSearchTransfer())
                ->setIdProductAbstract($productAbstractTransfer2->getIdProductAbstractOrFail())
                ->setSku($productAbstractTransfer2->getSkuOrFail()),
        ];

        // Act
        $filteredProductPageSearchTransfers = $this->tester
            ->getFacade()
            ->filterProductPageSearchCollection($productPageSearchTransfers);

        // Assert
        $this->assertCount(1, $filteredProductPageSearchTransfers);
        $this->assertEquals(
            $productAbstractTransfer1->getIdProductAbstractOrFail(),
            $filteredProductPageSearchTransfers[0]->getIdProductAbstract(),
        );
    }

    /**
     * @return void
     */
    public function testFilterProductPageSearchCollectionDoesNotFilterOutApprovedProducts(): void
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_1_SKU,
        ]);
        $productAbstractTransfer2 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_2_SKU,
        ]);
        $productPageSearchTransfers = [
            (new ProductPageSearchTransfer())
                ->setIdProductAbstract($productAbstractTransfer1->getIdProductAbstractOrFail())
                ->setSku($productAbstractTransfer1->getSkuOrFail()),

            (new ProductPageSearchTransfer())
                ->setIdProductAbstract($productAbstractTransfer2->getIdProductAbstractOrFail())
                ->setSku($productAbstractTransfer2->getSkuOrFail()),
        ];

        // Act
        $filteredProductPageSearchTransfers = $this->tester
            ->getFacade()
            ->filterProductPageSearchCollection($productPageSearchTransfers);

        // Assert
        $this->assertCount(2, $filteredProductPageSearchTransfers);
    }

    /**
     * @dataProvider getNotApprovedApprovalStatuses
     *
     * @param string|null $approvalStatus
     *
     * @return void
     */
    public function testFilterProductConcreteCollectionFiltersOutNotApprovedProducts(?string $approvalStatus): void
    {
        // Arrange
        $productConcreteTransfer1 = $this->tester->haveProduct(
            [],
            [
                ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
                ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_1_SKU,
            ],
        );
        $productConcreteTransfer2 = $this->tester->haveProduct(
            [],
            [
                ProductAbstractTransfer::APPROVAL_STATUS => $approvalStatus,
                ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_2_SKU,
            ],
        );
        $productConcreteTransfers = [$productConcreteTransfer1, $productConcreteTransfer2];

        // Act
        $filteredProductConcreteTransfers = $this->tester
            ->getFacade()
            ->filterProductConcreteCollection($productConcreteTransfers);

        // Assert
        $this->assertCount(1, $filteredProductConcreteTransfers);
        $this->assertEquals(
            $productConcreteTransfer1->getIdProductConcrete(),
            $filteredProductConcreteTransfers[0]->getIdProductConcrete(),
        );
    }

    /**
     * @return void
     */
    public function testFilterProductConcreteCollectionDoesNotFilterOutApprovedProducts(): void
    {
        // Arrange
        $productConcreteTransfer1 = $this->tester->haveProduct(
            [],
            [ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED],
        );
        $productConcreteTransfer2 = $this->tester->haveProduct(
            [],
            [ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED],
        );

        $productConcreteTransfers = [$productConcreteTransfer1, $productConcreteTransfer2];

        // Act
        $filteredProductConcreteTransfers = $this->tester
            ->getFacade()
            ->filterProductConcreteCollection($productConcreteTransfers);

        // Assert
        $this->assertCount(2, $filteredProductConcreteTransfers);
    }

    /**
     * @dataProvider getNotApprovedApprovalStatuses
     *
     * @param string|null $approvalStatus
     *
     * @return void
     */
    public function testFilterCartItemsFiltersOutNotApprovedProducts(?string $approvalStatus): void
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_1_SKU,
        ]);
        $productAbstractTransfer2 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => $approvalStatus,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_2_SKU,
        ]);
        $productConcreteTransfer1 = $this->tester->haveProduct([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer1->getIdProductAbstract(),
        ]);
        $productConcreteTransfer2 = $this->tester->haveProduct([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer2->getIdProductAbstract(),
        ]);

        $quoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())
                ->setAbstractSku($productAbstractTransfer1->getSkuOrFail())
                ->setIdProductAbstract($productAbstractTransfer1->getIdProductAbstractOrFail())
                ->setSku($productConcreteTransfer1->getSkuOrFail()))
            ->addItem((new ItemTransfer())
                ->setAbstractSku($productAbstractTransfer2->getSkuOrFail())
                ->setIdProductAbstract($productAbstractTransfer2->getIdProductAbstractOrFail())
                ->setSku($productConcreteTransfer2->getSkuOrFail()));

        // Act
        $quoteTransfer = $this->tester
            ->getFacade()
            ->filterCartItems($quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getItems());
        $this->assertEquals(
            $quoteTransfer->getItems()->offsetGet(0)->getIdProductAbstractOrFail(),
            $productAbstractTransfer1->getIdProductAbstractOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testFilterCartItemsDoesNotFilterOutApprovedProducts(): void
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
        ]);
        $productAbstractTransfer2 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
        ]);
        $productConcreteTransfer1 = $this->tester->haveProduct([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer1->getIdProductAbstract(),
        ]);
        $productConcreteTransfer2 = $this->tester->haveProduct([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer2->getIdProductAbstract(),
        ]);

        $quoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())
                ->setAbstractSku($productAbstractTransfer1->getSkuOrFail())
                ->setIdProductAbstract($productAbstractTransfer1->getIdProductAbstractOrFail())
                ->setSku($productConcreteTransfer1->getSkuOrFail()))
            ->addItem((new ItemTransfer())
                ->setAbstractSku($productAbstractTransfer2->getSkuOrFail())
                ->setIdProductAbstract($productAbstractTransfer2->getIdProductAbstractOrFail())
                ->setSku($productConcreteTransfer2->getSkuOrFail()));

        // Act
        $quoteTransfer = $this->tester
            ->getFacade()
            ->filterCartItems($quoteTransfer);

        // Assert
        $this->assertCount(2, $quoteTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testValidateCartChangeReturnsSuccessResponseForApprovedProduct(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([], [
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
        ]);
        $cartChangeTransfer = (new CartChangeTransfer())->addItem(
            (new ItemTransfer())
                ->setSku($productConcreteTransfer->getSku())
                ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract()),
        );

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateCartChange($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @dataProvider getNotApprovedApprovalStatuses
     *
     * @param string|null $approvalStatus
     *
     * @return void
     */
    public function testValidateCartChangeReturnsErrorResponseForNotApprovedProducts(?string $approvalStatus): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([], [
            ProductAbstractTransfer::APPROVAL_STATUS => $approvalStatus,
        ]);
        $cartChangeTransfer = (new CartChangeTransfer())->addItem(
            (new ItemTransfer())
                ->setSku($productConcreteTransfer->getSku())
                ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract()),
        );

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateCartChange($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testValidateQuoteForCheckoutReturnsSuccessResponseForApprovedProduct(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
        ]);
        $quoteTransfer = (new QuoteTransfer())->addItem(
            (new ItemTransfer())
                ->setAbstractSku($productAbstractTransfer->getSku())
                ->setIdProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail()),
        );

        // Act
        $checkoutResponse = $this->tester->getFacade()
            ->validateQuoteForCheckout($quoteTransfer, (new CheckoutResponseTransfer()));

        // Assert
        $this->assertTrue($checkoutResponse);
    }

    /**
     * @dataProvider getNotApprovedApprovalStatuses
     *
     * @param string|null $approvalStatus
     *
     * @return void
     */
    public function testValidateQuoteForCheckoutReturnsErrorResponseForNotApprovedProducts(?string $approvalStatus): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => $approvalStatus,
        ]);
        $quoteTransfer = (new QuoteTransfer())->addItem(
            (new ItemTransfer())
                ->setAbstractSku($productAbstractTransfer->getSku())
                ->setIdProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail()),
        );

        // Act
        $checkoutResponse = $this->tester->getFacade()
            ->validateQuoteForCheckout(
                $quoteTransfer,
                (new CheckoutResponseTransfer()),
            );

        // Assert
        $this->assertFalse($checkoutResponse);
    }

    /**
     * @return void
     */
    public function testValidateShoppingListItemReturnsSuccessResponseForApprovedProduct(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([], [
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
        ]);
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract());

        // Act
        $shoppingListPreAddItemCheckResponseTransfer = $this->tester->getFacade()
            ->validateShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertTrue($shoppingListPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @dataProvider getNotApprovedApprovalStatuses
     *
     * @param string|null $approvalStatus
     *
     * @return void
     */
    public function testValidateShoppingListItemReturnsErrorResponseForNotApprovedProducts(?string $approvalStatus): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([], [
            ProductAbstractTransfer::APPROVAL_STATUS => $approvalStatus,
        ]);
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract());

        // Act
        $shoppingListPreAddItemCheckResponseTransfer = $this->tester->getFacade()
            ->validateShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertFalse($shoppingListPreAddItemCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $shoppingListPreAddItemCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testExpandProductAbstractDoesNotExpandWhenProductAbstractHasApprovalStatus(): void
    {
        // Arrange
        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setApprovalStatus(ProductApprovalConfig::STATUS_APPROVED);

        // Act
        $productAbstractTransfer = $this->tester->getFacade()->expandProductAbstract($productAbstractTransfer);

        // Assert
        $this->assertSame(ProductApprovalConfig::STATUS_APPROVED, $productAbstractTransfer->getApprovalStatusOrFail());
    }

    /**
     * @return void
     */
    public function testExpandProductAbstractWithDefaultApprovalStatus(): void
    {
        // Act
        $productAbstractTransfer = $this->tester->getFacade()->expandProductAbstract(new ProductAbstractTransfer());

        // Assert
        $this->assertSame(
            $this->tester->getModuleConfig()->getDefaultProductApprovalStatus(),
            $productAbstractTransfer->getApprovalStatusOrFail(),
        );
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function getNotApprovedApprovalStatuses(): array
    {
        return [
            [ProductApprovalSharedConfig::STATUS_DRAFT],
            [ProductApprovalSharedConfig::STATUS_WAITING_FOR_APPROVAL],
            [ProductApprovalSharedConfig::STATUS_DENIED],
            [null],
        ];
    }
}
