<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingListProductOption\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingList;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListItem;
use Orm\Zed\ShoppingListProductOption\Persistence\SpyShoppingListProductOption;
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
     * @var \Orm\Zed\ShoppingList\Persistence\SpyShoppingListItem
     */
    protected $shoppingListItemEntity;

    /**
     * @var \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected $productOptionValue1Entity;

    /**
     * @var \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected $productOptionValue2Entity;

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

        $customerEntity = (new SpyCustomerQuery())
            ->filterByCustomerReference('TEST_CUSTOMER')
            ->filterByEmail('TEST_EMAIL')
            ->findOneOrCreate();
        $customerEntity->save();

        $shoppingListEntity = (new SpyShoppingList())
            ->setName('test')
            ->setCustomerReference($customerEntity->getCustomerReference());
        $shoppingListEntity->save();

        $this->shoppingListItemEntity = (new SpyShoppingListItem())
            ->setFkShoppingList($shoppingListEntity->getIdShoppingList())
            ->setSku('test');

        $this->shoppingListItemEntity->save();

        $this->productOptionValue1Entity = $this->createProductOptionValue();
        $this->productOptionValue2Entity = $this->createProductOptionValue('sku_for_testing_2');
    }

    /**
     * @return void
     */
    public function testGetShoppingListItemProductOptionsByIdShoppingListItemWithExistOptions(): void
    {
        $shoppingListProductOption1 = (new SpyShoppingListProductOption())
            ->setFkProductOptionValue($this->productOptionValue1Entity->getIdProductOptionValue())
            ->setFkShoppingListItem($this->shoppingListItemEntity->getIdShoppingListItem());
        $shoppingListProductOption1->save();

        $shoppingListProductOption2 = (new SpyShoppingListProductOption())
            ->setFkProductOptionValue($this->productOptionValue2Entity->getIdProductOptionValue())
            ->setFkShoppingListItem($this->shoppingListItemEntity->getIdShoppingListItem());
        $shoppingListProductOption2->save();

        $actualResult = $this->tester
            ->getFacade()
            ->getShoppingListItemProductOptionsByIdShoppingListItem(
                $this->shoppingListItemEntity->getIdShoppingListItem()
            );

        $expectedResult = [
            $this->productOptionValue1Entity->getIdProductOptionValue(),
            $this->productOptionValue2Entity->getIdProductOptionValue(),
        ];

        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertTrue(in_array($productOption->getIdProductOptionValue(), $expectedResult));
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
                $this->shoppingListItemEntity->getIdShoppingListItem()
            );

        $expectedResult = [];

        $this->assertSameSize($actualResult->getProductOptions(), $expectedResult);
    }

    /**
     * @return void
     */
    public function testSaveShoppingListItemProductOptionsSavesWithSetUpOptions(): void
    {
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($this->shoppingListItemEntity->getIdShoppingListItem())
            ->setProductOptions(new ArrayObject([
                $this->productOptionValue1Entity->getIdProductOptionValue(),
                $this->productOptionValue2Entity->getIdProductOptionValue(),
                ]));

        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);
        $actualResult = $this->tester
            ->getFacade()
            ->getShoppingListItemProductOptionsByIdShoppingListItem(
                $this->shoppingListItemEntity->getIdShoppingListItem()
            );

        $expectedResult = [
            $this->productOptionValue1Entity->getIdProductOptionValue(),
            $this->productOptionValue2Entity->getIdProductOptionValue(),
        ];

        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertTrue(in_array($productOption->getIdProductOptionValue(), $expectedResult));
        }

        $this->assertSameSize($actualResult->getProductOptions(), $expectedResult);
    }

    /**
     * @return void
     */
    public function testSaveShoppingListItemProductOptionsSavesWithoutSetUpOptions(): void
    {
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($this->shoppingListItemEntity->getIdShoppingListItem())
            ->setProductOptions(new ArrayObject([]));

        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);
        $actualResult = $this->tester
            ->getFacade()
            ->getShoppingListItemProductOptionsByIdShoppingListItem(
                $this->shoppingListItemEntity->getIdShoppingListItem()
            );

        $expectedResult = [];

        $this->assertSameSize($actualResult->getProductOptions(), $expectedResult);
    }

    /**
     * @return void
     */
    public function testSaveShoppingListItemProductOptionsRemovesOldOptionAndSavesNewOption(): void
    {
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($this->shoppingListItemEntity->getIdShoppingListItem())
            ->setProductOptions(new ArrayObject([
                $this->productOptionValue1Entity->getIdProductOptionValue(),
            ]));
        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);

        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($this->shoppingListItemEntity->getIdShoppingListItem())
            ->setProductOptions(new ArrayObject([
                $this->productOptionValue2Entity->getIdProductOptionValue(),
            ]));
        $this->tester->getFacade()->saveShoppingListItemProductOptions($shoppingListItemTransfer);

        $actualResult = $this->tester
            ->getFacade()
            ->getShoppingListItemProductOptionsByIdShoppingListItem(
                $this->shoppingListItemEntity->getIdShoppingListItem()
            );

        $expectedResult = [
            $this->productOptionValue2Entity->getIdProductOptionValue(),
        ];

        foreach ($actualResult->getProductOptions() as $productOption) {
            $this->assertTrue(in_array($productOption->getIdProductOptionValue(), $expectedResult));
        }

        $this->assertSameSize($actualResult->getProductOptions(), $expectedResult);
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected function createProductOptionValue(string $sku = 'sku_for_testing'): SpyProductOptionValue
    {
        $productOptionGroup = (new SpyProductOptionGroupQuery())
            ->filterByName('test')
            ->findOneOrCreate();
        $productOptionGroup->save();

        $productOptionValue = (new SpyProductOptionValueQuery())
            ->filterByValue('value.translation.key')
            ->filterByFkProductOptionGroup($productOptionGroup->getIdProductOptionGroup())
            ->filterBySku($sku)
            ->findOneOrCreate();
        $productOptionValue->save();

        return $productOptionValue;
    }
}
