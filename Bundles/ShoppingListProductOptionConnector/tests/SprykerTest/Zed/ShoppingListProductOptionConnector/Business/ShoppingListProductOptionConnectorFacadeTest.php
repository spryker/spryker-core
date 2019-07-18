<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingListProductOptionConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Orm\Zed\ShoppingListProductOptionConnector\Persistence\SpyShoppingListProductOption;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface;
use Spryker\Zed\ShoppingList\Communication\Plugin\ShoppingListPermissionStoragePlugin;
use Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOptionConnectorBusinessFactory;
use SprykerTest\Zed\ShoppingListProductOptionConnector\Stub\ShoppingListProductOptionConnectorConfigStub;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShoppingListProductOptionConnector
 * @group Business
 * @group Facade
 * @group ShoppingListProductOptionConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ShoppingListProductOptionConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ShoppingListProductOptionConnector\ShoppingListProductOptionConnectorBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected $shoppingListItemTransferAssigned;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected $shoppingListItemTransferUnassigned;

    /**
     * @var \Generated\Shared\Transfer\ProductOptionValueTransfer
     */
    protected $productOptionValueTransferActive;

    /**
     * @var \Generated\Shared\Transfer\ProductOptionValueTransfer
     */
    protected $productOptionValueTransferActive2;

    /**
     * @var \Generated\Shared\Transfer\ProductOptionValueTransfer
     */
    protected $productOptionValueTransferInactive;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransferAssigned;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransferUnassigned;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $config = new ShoppingListProductOptionConnectorConfigStub();

        $factory = new ShoppingListProductOptionConnectorBusinessFactory();
        $factory->setConfig($config);

        $facade = $this->tester->getFacade();
        $facade->setFactory($factory);

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
        $this->productConcreteTransferAssigned = $this->tester->haveProduct();
        $this->productConcreteTransferUnassigned = $this->tester->haveProduct();
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

        $this->shoppingListItemTransferAssigned = $this->tester->haveShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->productConcreteTransferAssigned->getSku(),
        ]);

        $this->shoppingListItemTransferUnassigned = $this->tester->haveShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->productConcreteTransferUnassigned->getSku(),
        ]);

        // set createProductOptionGroupValue to Active ProductOptionGroup
        $this->productOptionValueTransferActive = $this->tester->createProductOptionGroupValueTransfer(
            [
                ProductOptionValueTransfer::SKU => 'PO_Group_0_Value_1',
                ProductOptionGroupTransfer::ACTIVE => true,
            ]
        );
        // set createProductOptionGroupValue to Active ProductOptionGroup
        $this->productOptionValueTransferActive2 = $this->tester->createProductOptionGroupValueTransfer(
            [
                ProductOptionValueTransfer::SKU => 'PO_Group_2_Value_1',
                ProductOptionGroupTransfer::ACTIVE => true,
            ]
        );
        // set createProductOptionGroupValue to Inactive ProductOptionGroup
        $this->productOptionValueTransferInactive = $this->tester->createProductOptionGroupValueTransfer(
            [
                ProductOptionValueTransfer::SKU => 'PO_Group_3',
                ProductOptionGroupTransfer::ACTIVE => false,
            ]
        );

        // Assign Product Abstract to createProductOptionGroupValue with Active ProductOptionGroup
        $this->getProductOptionFacade()->addProductAbstractToProductOptionGroup($this->productConcreteTransferAssigned->getAbstractSku(), $this->productOptionValueTransferActive->getFkProductOptionGroup());
        // Assign Product Abstract to createProductOptionGroupValue with Active ProductOptionGroup
        $this->getProductOptionFacade()->addProductAbstractToProductOptionGroup($this->productConcreteTransferAssigned->getAbstractSku(), $this->productOptionValueTransferActive2->getFkProductOptionGroup());
        // Assign Product Abstract to createProductOptionGroupValue with Inactive ProductOptionGroup
        $this->getProductOptionFacade()->addProductAbstractToProductOptionGroup($this->productConcreteTransferAssigned->getAbstractSku(), $this->productOptionValueTransferInactive->getFkProductOptionGroup());
        /*
         * $this->shoppingListItemTransferUnassigned have $productTransfer2 that remains unassigned to any ProductOptionGroup
         */
    }

    /**
     * @return void
     */
    public function testSaveShoppingListItemProductOptionsAssignedToGroupSavesWithSetUpActiveOptions(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->getAssignedShoppingListItemCopy()
            ->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransferActive->getIdProductOptionValue()
                )
            )->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransferActive2->getIdProductOptionValue()
                )
            )->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransferInactive->getIdProductOptionValue()
                )
            );

        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);

        $expectedResult = [
            $this->productOptionValueTransferActive->getIdProductOptionValue(),
            $this->productOptionValueTransferActive2->getIdProductOptionValue(),
        ];

        // Act
        $actualResult = $this->tester->getFacade()
            ->expandShoppingListItemWithProductOptions(
                $this->getAssignedShoppingListItemCopy()
            );

        // Assert
        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertContains($productOption->getIdProductOptionValue(), $expectedResult);
        }
        $this->assertSameSize($actualResult->getProductOptions(), $expectedResult);
    }

    /**
     * @return void
     */
    public function testSaveShoppingListItemProductOptionsUnassignedToAnyGroupSavesWithoutAnyOfSetUpOptions(): void
    {
        // Arrange
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($this->shoppingListItemTransferUnassigned->getIdShoppingListItem())
            ->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransferActive->getIdProductOptionValue()
                )
            )
            ->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransferActive2->getIdProductOptionValue()
                )
            )->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransferInactive->getIdProductOptionValue()
                )
            );

        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);

        $expectedResult = [];

        // Act
        $actualResult = $this->tester->getFacade()
            ->expandShoppingListItemWithProductOptions(
                $this->getUnassignedShoppingListItemCopy()
            );

        // Assert
        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertContains($productOption->getIdProductOptionValue(), $expectedResult);
        }

        $this->assertSameSize($actualResult->getProductOptions(), $expectedResult);
    }

    /**
     * @return void
     */
    public function testSaveShoppingListItemProductOptionsSavesWithoutSetUpOptions(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->getAssignedShoppingListItemCopy();

        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);

        // Act
        $actualResult = $this->tester->getFacade()
            ->expandShoppingListItemWithProductOptions(
                $this->getAssignedShoppingListItemCopy()
            );

        // Assert
        $this->assertEmpty($actualResult->getProductOptions());
    }

    /**
     * @return void
     */
    public function testSaveShoppingListItemProductOptionsAssignedToGroupRemovesOldOptionAndSavesOnlyNewActiveOption(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->getAssignedShoppingListItemCopy()
            ->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransferActive->getIdProductOptionValue()
                )
            );

        // Act
        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);

        // Arrange
        $shoppingListItemTransfer = $this->getAssignedShoppingListItemCopy()
            ->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransferActive2->getIdProductOptionValue()
                )
            )->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransferInactive->getIdProductOptionValue()
                )
            );

        // Act
        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);

        $actualResult = $this->tester->getFacade()
            ->expandShoppingListItemWithProductOptions(
                $this->getAssignedShoppingListItemCopy()
            );

        $expectedResult = [
            $this->productOptionValueTransferActive2->getIdProductOptionValue(),
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
    public function testDeleteShoppingListItemProductOptionsByRemovedProductOptionValuesRemovesMarkedForRemovalProductOptions(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->getAssignedShoppingListItemCopy()
            ->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransferActive->getIdProductOptionValue()
                )
            )
            ->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransferActive2->getIdProductOptionValue()
                )
            );

        // Act
        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);

        $this->tester->getFacade()
            ->deleteShoppingListItemProductOptionsByRemovedProductOptionValues(
                (new ProductOptionGroupTransfer())
                    ->addProductOptionValuesToBeRemoved($this->productOptionValueTransferActive2->getIdProductOptionValue())
            );

        $actualResult = $this->tester->getFacade()
            ->expandShoppingListItemWithProductOptions(
                $this->getAssignedShoppingListItemCopy()
            );

        $expectedResult = [
            $this->productOptionValueTransferActive->getIdProductOptionValue(),
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
    public function testExpandShoppingListItemWithProductOptionsAssignedToGroupExpandsOnlyWithActiveOptions(): void
    {
        // Arrange
        $this->tester->cleanUpShoppingListProductOptionConnectorByIdShoppingListItem($this->shoppingListItemTransferAssigned->getIdShoppingListItem());
        $this->tester->assureShoppingListProductOptionConnector($this->shoppingListItemTransferAssigned->getIdShoppingListItem(), $this->productOptionValueTransferActive->getIdProductOptionValue());
        $this->tester->assureShoppingListProductOptionConnector($this->shoppingListItemTransferAssigned->getIdShoppingListItem(), $this->productOptionValueTransferInactive->getIdProductOptionValue());

        $shoppingListItemTransfer = $this->getAssignedShoppingListItemCopy();

        // Act
        $actualResult = $this->tester->getFacade()->expandShoppingListItemWithProductOptions($shoppingListItemTransfer);

        $expectedResult = [
            $this->productOptionValueTransferActive->getIdProductOptionValue(),
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
        $this->tester->cleanUpShoppingListProductOptionConnectorByIdShoppingListItem($this->shoppingListItemTransferAssigned->getIdShoppingListItem());
        $this->tester->assureShoppingListProductOptionConnector($this->shoppingListItemTransferAssigned->getIdShoppingListItem(), $this->productOptionValueTransferActive->getIdProductOptionValue());

        $shoppingListItemTransfer = $this->getAssignedShoppingListItemCopy();

        $actualResult = $this->tester->getFacade()->expandShoppingListItemWithProductOptions($shoppingListItemTransfer);

        $expectedResult = [
            $this->productOptionValueTransferActive->getIdProductOptionValue(),
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
        // Arrange
        $this->tester->cleanUpShoppingListProductOptionConnectorByIdShoppingListItem($this->shoppingListItemTransferAssigned->getIdShoppingListItem());
        $shoppingListItemTransfer = $this->getAssignedShoppingListItemCopy();

        // Act
        $actualResult = $this->tester->getFacade()->expandShoppingListItemWithProductOptions($shoppingListItemTransfer);

        // Assert
        foreach ($actualResult->getProductOptions() as $productOptions) {
            $this->assertEmpty($productOptions);
        }
    }

    /**
     * @return void
     */
    public function testExpandShoppingListItemWithProductOptions(): void
    {
        // Arrange
        $shoppingListProductOption1 = (new SpyShoppingListProductOption())
            ->setFkProductOptionValue($this->productOptionValueTransferActive->getIdProductOptionValue())
            ->setFkShoppingListItem($this->shoppingListItemTransferAssigned->getIdShoppingListItem());
        $shoppingListProductOption1->save();

        $shoppingListProductOption2 = (new SpyShoppingListProductOption())
            ->setFkProductOptionValue($this->productOptionValueTransferInactive->getIdProductOptionValue())
            ->setFkShoppingListItem($this->shoppingListItemTransferAssigned->getIdShoppingListItem());
        $shoppingListProductOption2->save();

        // Act
        $actualResult = $this->tester->getFacade()
            ->expandShoppingListItemWithProductOptions(
                $this->getAssignedShoppingListItemCopy()
            );

        $expectedProductOptionValueIds = [
            $this->productOptionValueTransferActive->getIdProductOptionValue(),
        ];

        // Assert
        $this->assertCount(1, $actualResult->getProductOptions());
        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertTrue(in_array($productOption->getIdProductOptionValue(), $expectedProductOptionValueIds));
        }
    }

    /**
     * @return void
     */
    public function testMapCartItemProductOptionToShoppingListItemProductOption(): void
    {
        // Prepare
        $shoppingListItemTransfer = new ShoppingListItemTransfer();
        $productOptionTransfer = (new ProductOptionTransfer())
            ->setIdProductOptionValue($this->productOptionValueTransferActive->getIdProductOptionValue())
            ->setValue($this->productOptionValueTransferActive->getValue());
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

    /**
     * @return void
     */
    public function testRemoveShoppingListItemProductOptions(): void
    {
        $shoppingListProductOption1 = (new SpyShoppingListProductOption())
            ->setFkProductOptionValue($this->productOptionValueTransferActive->getIdProductOptionValue())
            ->setFkShoppingListItem($this->shoppingListItemTransferAssigned->getIdShoppingListItem());
        $shoppingListProductOption1->save();

        $shoppingListProductOption2 = (new SpyShoppingListProductOption())
            ->setFkProductOptionValue($this->productOptionValueTransferInactive->getIdProductOptionValue())
            ->setFkShoppingListItem($this->shoppingListItemTransferAssigned->getIdShoppingListItem());
        $shoppingListProductOption2->save();

        $this->tester->getFacade()
            ->removeShoppingListItemProductOptions(
                $this->shoppingListItemTransferAssigned->getIdShoppingListItem()
            );

        $actualResult = $this->tester->getFacade()
            ->expandShoppingListItemWithProductOptions(
                $this->getAssignedShoppingListItemCopy()
            );

        $this->assertEmpty($actualResult->getProductOptions());
    }

    /**
     * @return void
     */
    public function testExpandShoppingListItemWithProductOptionsFirstUnassignedToGroupAndHadHadNotOptionsThenAssignedAgainExpandsAgainWithOption(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->getUnassignedShoppingListItemCopy()
            ->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransferActive->getIdProductOptionValue()
                )
            );

        // Act
        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);

        $actualResult = $this->tester->getFacade()
            ->expandShoppingListItemWithProductOptions(
                $this->getUnassignedShoppingListItemCopy()
            );

        // Assert
        $this->assertEmpty($actualResult->getProductOptions());

        $this->getProductOptionFacade()->addProductAbstractToProductOptionGroup($this->productConcreteTransferUnassigned->getAbstractSku(), $this->productOptionValueTransferActive->getFkProductOptionGroup());

        //Act
        $actualResult = $this->tester->getFacade()
            ->expandShoppingListItemWithProductOptions(
                $this->getUnassignedShoppingListItemCopy()
            );

        // Assert
        $expectedResult = [
            $this->productOptionValueTransferActive->getIdProductOptionValue(),
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
    public function testExpandShoppingListItemWithProductOptionsFirstInactiveGroupHadHadNotOptionsThenActiveAgainExpandsAgainWithOption(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->getAssignedShoppingListItemCopy()
            ->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue(
                    $this->productOptionValueTransferInactive->getIdProductOptionValue()
                )
            );

        // Act
        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);

        $actualResult = $this->tester->getFacade()
            ->expandShoppingListItemWithProductOptions(
                $this->getAssignedShoppingListItemCopy()
            );

        // Assert
        $this->assertEmpty($actualResult->getProductOptions());

        $this->getProductOptionFacade()->toggleOptionActive($this->productOptionValueTransferInactive->getFkProductOptionGroup(), true);

        //Act
        $actualResult = $this->tester->getFacade()
            ->expandShoppingListItemWithProductOptions(
                $this->getAssignedShoppingListItemCopy()
            );

        // Assert
        $expectedResult = [
            $this->productOptionValueTransferInactive->getIdProductOptionValue(),
        ];

        // Assert
        $this->assertSameSize($actualResult->getProductOptions(), $expectedResult);
        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertContains($productOption->getIdProductOptionValue(), $expectedResult);
        }
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface
     */
    protected function getProductOptionFacade(): ProductOptionFacadeInterface
    {
        return $this->tester->getLocator()->productOption()->facade();
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function getAssignedShoppingListItemCopy(): ShoppingListItemTransfer
    {
        return (new ShoppingListItemTransfer())
            ->fromArray($this->shoppingListItemTransferAssigned->toArray());
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function getUnassignedShoppingListItemCopy(): ShoppingListItemTransfer
    {
        return (new ShoppingListItemTransfer())
            ->fromArray($this->shoppingListItemTransferUnassigned->toArray());
    }
}
