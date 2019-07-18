<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep;

use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListTableMap;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShoppingListDataImport\Business\DataSet\ShoppingListItemDataSetInterface;

class ShoppingListKeyToIdShoppingList implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idShoppingListCache;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $shoppingListKey = $dataSet[ShoppingListItemDataSetInterface::COLUMN_SHOPPING_LIST_KEY];

        if (!isset($this->idShoppingListCache[$shoppingListKey])) {
            $shoppingListQuery = new SpyShoppingListQuery();
            $idShoppingList = $shoppingListQuery
                ->select([SpyShoppingListTableMap::COL_ID_SHOPPING_LIST])
                ->findOneByKey($shoppingListKey);

            if (!$idShoppingList) {
                throw new EntityNotFoundException(sprintf('Could not find shopping list by key "%s"', $shoppingListKey));
            }

            $this->idShoppingListCache[$shoppingListKey] = $idShoppingList;
        }

        $dataSet[ShoppingListItemDataSetInterface::ID_SHOPPING_LIST] = $this->idShoppingListCache[$shoppingListKey];
    }
}
