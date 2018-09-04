<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingListProductOption\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Orm\Zed\ShoppingListProductOption\Persistence\SpyShoppingListProductOption;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\ShoppingList\Communication\Plugin\ShoppingListPermissionStoragePlugin;
use Spryker\Zed\ShoppingListProductOption\Business\ShoppingListProductOptionBusinessFactory;
use SprykerTest\Zed\ShoppingListProductOption\Stub\ShoppingListProductOptionConfigStub;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShoppingListProductOption
 * @group Business
 * @group Facade
 * @group ShoppingListProductOptionFacadeTest
 * Add your own group annotations below this line
 */
class ShoppingListProductOptionFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ShoppingListProductOption\ShoppingListProductOptionBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected $shoppingListItemTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductOptionValueTransfer
     */
    protected $productOptionValueTransfer1;

    /**
     * @var \Generated\Shared\Transfer\ProductOptionValueTransfer
     */
    protected $productOptionValueTransfer2;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $config = new ShoppingListProductOptionConfigStub();
        $factory = new ShoppingListProductOptionBusinessFactory();
        $factory->setConfig($config);
        $this->tester->getFacade()->setFactory($factory);

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION_STORAGE, [
            new ShoppingListPermissionStoragePlugin(),
        ]);

        $this->prepareData();
    }

    /**
     * @return void
     */
    protected function prepareData(): void
    {
        $customerTransfer = $this->tester->haveCustomer();
        $productTransfer = $this->tester->haveProduct();
        $productTransfer2 = $this->tester->haveProduct();
        $companyTransfer = $this->tester->haveCompany();

        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);

        $shoppingListTransfer = $this->tester->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
        ]);

        $this->shoppingListItemTransfer = $this->tester->haveShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $productTransfer->getSku(),
        ]);

        $this->productOptionValueTransfer1 = $this->tester->createProductOptionGroupValueTransfer($productTransfer->getSku());
        $this->productOptionValueTransfer2 = $this->tester->createProductOptionGroupValueTransfer($productTransfer2->getSku());
    }

    /**
     * @return void
     */
    public function testGetShoppingListItemProductOptionsByIdShoppingListItemWithExistOptions(): void
    {
        $shoppingListProductOption1 = (new SpyShoppingListProductOption())
            ->setFkProductOptionValue($this->productOptionValueTransfer1->getIdProductOptionValue())
            ->setFkShoppingListItem($this->shoppingListItemTransfer->getIdShoppingListItem());
        $shoppingListProductOption1->save();

        $shoppingListProductOption2 = (new SpyShoppingListProductOption())
            ->setFkProductOptionValue($this->productOptionValueTransfer2->getIdProductOptionValue())
            ->setFkShoppingListItem($this->shoppingListItemTransfer->getIdShoppingListItem());
        $shoppingListProductOption2->save();

        $actualResult = $this->tester
            ->getFacade()
            ->getShoppingListItemProductOptionsByIdShoppingListItem(
                $this->shoppingListItemTransfer->getIdShoppingListItem()
            );

        $expectedResult = [
            $this->productOptionValueTransfer1->getIdProductOptionValue(),
            $this->productOptionValueTransfer2->getIdProductOptionValue(),
        ];

        // Assert
        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertContains($productOption->getIdProductOptionValue(), $expectedResult);
        }

        $this->assertSameSize($actualResult->getProductOptions(), $expectedResult);
    }

    /**
     * @return void
     */
    public function testGetShoppingListItemProductOptionsByIdShoppingListItemWithoutExistOptions(): void
    {
        $actualResult = $this->tester
            ->getFacade()
            ->getShoppingListItemProductOptionsByIdShoppingListItem(
                $this->shoppingListItemTransfer->getIdShoppingListItem()
            );

        $expectedResult = [];

        // Assert
        $this->assertSameSize($actualResult->getProductOptions(), $expectedResult);
    }

    /**
     * @return void
     */
    public function testSaveShoppingListItemProductOptionsSavesWithSetUpOptions(): void
    {
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($this->shoppingListItemTransfer->getIdShoppingListItem())
            ->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransfer1->getIdProductOptionValue()
                )
            )->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransfer2->getIdProductOptionValue()
                )
            );

        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);
        $actualResult = $this->tester
            ->getFacade()
            ->getShoppingListItemProductOptionsByIdShoppingListItem(
                $this->shoppingListItemTransfer->getIdShoppingListItem()
            );

        $expectedResult = [
            $this->productOptionValueTransfer1->getIdProductOptionValue(),
            $this->productOptionValueTransfer2->getIdProductOptionValue(),
        ];

        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertContains($productOption->getIdProductOptionValue(), $expectedResult);
        }

        // Assert
        $this->assertSameSize($actualResult->getProductOptions(), $expectedResult);
    }

    /**
     * @return void
     */
    public function testSaveShoppingListItemProductOptionsSavesWithoutSetUpOptions(): void
    {
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($this->shoppingListItemTransfer->getIdShoppingListItem());

        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);
        $actualResult = $this->tester
            ->getFacade()
            ->getShoppingListItemProductOptionsByIdShoppingListItem(
                $this->shoppingListItemTransfer->getIdShoppingListItem()
            );

        // Assert
        $this->assertEmpty($actualResult->getProductOptions());
    }

    /**
     * @return void
     */
    public function testSaveShoppingListItemProductOptionsRemovesOldOptionAndSavesNewOption(): void
    {
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($this->shoppingListItemTransfer->getIdShoppingListItem())
            ->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransfer1->getIdProductOptionValue()
                )
            );
        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);

        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($this->shoppingListItemTransfer->getIdShoppingListItem())
            ->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransfer2->getIdProductOptionValue()
                )
            );

        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);

        $actualResult = $this->tester
            ->getFacade()
            ->getShoppingListItemProductOptionsByIdShoppingListItem(
                $this->shoppingListItemTransfer->getIdShoppingListItem()
            );

        $expectedResult = [
            $this->productOptionValueTransfer2->getIdProductOptionValue(),
        ];

        // Assert
        $this->assertSameSize($actualResult->getProductOptions(), $expectedResult);
        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertContains($productOption->getIdProductOptionValue(), $expectedResult);
        }
    }

    /**
     * @return void
     */
    public function testExpandItemWithProductOptions(): void
    {
        $this->tester->cleanUpShoppingListProductOptionByIdShoppingListItem($this->shoppingListItemTransfer->getIdShoppingListItem());
        $this->tester->assureShoppingListProductOption($this->shoppingListItemTransfer->getIdShoppingListItem(), $this->productOptionValueTransfer1->getIdProductOptionValue());
        $this->tester->assureShoppingListProductOption($this->shoppingListItemTransfer->getIdShoppingListItem(), $this->productOptionValueTransfer2->getIdProductOptionValue());

        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($this->shoppingListItemTransfer->getIdShoppingListItem());

        $actualResult = $this->tester->getFacade()->expandItem($shoppingListItemTransfer);

        $expectedResult = [
            $this->productOptionValueTransfer1->getIdProductOptionValue(),
            $this->productOptionValueTransfer2->getIdProductOptionValue(),
        ];

        // Assert
        $this->assertSameSize($actualResult->getProductOptions(), $expectedResult);
        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertContains($productOption->getIdProductOptionValue(), $expectedResult);
        }
    }

    /**
     * @return void
     */
    public function testExpandItemWithProductOption(): void
    {
        $this->tester->cleanUpShoppingListProductOptionByIdShoppingListItem($this->shoppingListItemTransfer->getIdShoppingListItem());
        $this->tester->assureShoppingListProductOption($this->shoppingListItemTransfer->getIdShoppingListItem(), $this->productOptionValueTransfer1->getIdProductOptionValue());

        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($this->shoppingListItemTransfer->getIdShoppingListItem());

        $actualResult = $this->tester->getFacade()->expandItem($shoppingListItemTransfer);

        $expectedResult = [
            $this->productOptionValueTransfer1->getIdProductOptionValue(),
        ];

        // Assert
        $this->assertSameSize($actualResult->getProductOptions(), $expectedResult);
        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertContains($productOption->getIdProductOptionValue(), $expectedResult);
        }
    }

    /**
     * @return void
     */
    public function testExpandItemWithoutProductOption(): void
    {
        $this->tester->cleanUpShoppingListProductOptionByIdShoppingListItem($this->shoppingListItemTransfer->getIdShoppingListItem());
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($this->shoppingListItemTransfer->getIdShoppingListItem());

        $actualResult = $this->tester->getFacade()->expandItem($shoppingListItemTransfer);

        // Assert
        foreach ($actualResult->getProductOptions() as $productOptions) {
            $this->assertEmpty($productOptions);
        }
    }

    /**
     * @return void
     */
    public function testMapCartItemProductOptionToShoppingListItemProductOption(): void
    {
        // Prepare
        $shoppingListItemTransfer = new ShoppingListItemTransfer();
        $productOptionTransfer = (new ProductOptionTransfer())->setValue($this->productOptionValueTransfer1);
        $itemTransfer = (new ItemTransfer())->addProductOption($productOptionTransfer);

        // Action
        $actualResult = $this->tester->getFacade()->mapCartItemProductOptionsToShoppingListItemProductOptions(
            $itemTransfer,
            $shoppingListItemTransfer
        );

        // Assert
        $this->assertCount(1, $actualResult->getProductOptions());
        $this->assertContains($productOptionTransfer, $actualResult->getProductOptions());
    }
}
