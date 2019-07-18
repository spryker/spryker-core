<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep;

use Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShoppingListDataImport\Business\DataSet\ShoppingListItemDataSetInterface;

class ShoppingListItemWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $shoppingListItemEntity = $this->createShoppingListItemQuery()
            ->filterByFkShoppingList($dataSet[ShoppingListItemDataSetInterface::ID_SHOPPING_LIST])
            ->filterBySku($dataSet[ShoppingListItemDataSetInterface::COLUMN_PRODUCT_SKU])
            ->findOneOrCreate();

        $shoppingListItemEntity
            ->setQuantity($dataSet[ShoppingListItemDataSetInterface::COLUMN_QUANTITY])
            ->save();
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery
     */
    protected function createShoppingListItemQuery(): SpyShoppingListItemQuery
    {
        return SpyShoppingListItemQuery::create();
    }
}
