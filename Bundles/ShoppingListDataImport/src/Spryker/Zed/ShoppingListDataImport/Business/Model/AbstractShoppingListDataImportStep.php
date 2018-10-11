<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Business\Model;

use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListTableMap;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;

abstract class AbstractShoppingListDataImportStep
{
    /**
     * @var int[]
     */
    protected $idShoppingListCache = [];

    /**
     * @param string $shoppingListKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdShoppingListByKey(string $shoppingListKey): int
    {
        if (isset($this->idShoppingListCache[$shoppingListKey])) {
            return $this->idShoppingListCache[$shoppingListKey];
        }

        $idShoppingList = SpyShoppingListQuery::create()
            ->filterByKey($shoppingListKey)
            ->select(SpyShoppingListTableMap::COL_ID_SHOPPING_LIST)
            ->findOne();

        if (!$idShoppingList) {
            throw new EntityNotFoundException(
                sprintf('Shopping List with key "%s" was not found during data import.', $shoppingListKey)
            );
        }

        $this->idShoppingListCache[$shoppingListKey] = $idShoppingList;

        return $idShoppingList;
    }
}
