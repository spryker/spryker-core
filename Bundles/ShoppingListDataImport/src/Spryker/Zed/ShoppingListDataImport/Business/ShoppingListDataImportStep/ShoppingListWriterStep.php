<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep;

use Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShoppingListDataImport\Business\DataSet\ShoppingListDataSetInterface;

class ShoppingListWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $shoppingListEntity = $this->createShoppingListQuery()
            ->filterByKey($dataSet[ShoppingListDataSetInterface::COLUMN_KEY])
            ->findOneOrCreate();

        $shoppingListEntity
            ->setName($dataSet[ShoppingListDataSetInterface::COLUMN_NAME])
            ->setCustomerReference($dataSet[ShoppingListDataSetInterface::COLUMN_OWNER_CUSTOMER_REFERENCE])
            ->save();
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery
     */
    protected function createShoppingListQuery(): SpyShoppingListQuery
    {
        return SpyShoppingListQuery::create();
    }
}
