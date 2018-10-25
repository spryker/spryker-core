<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep;

use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListPermissionGroupTableMap;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroupQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShoppingListDataImport\Business\DataSet\ShoppingListCompanyUserDataSetInterface;

class ShoppingListPermissionGroupNameToIdShoppingListPermissionGroupStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idPermissionGroupCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $shoppingListPermissionGroupName = $dataSet[ShoppingListCompanyUserDataSetInterface::COLUMN_PERMISSION_GROUP_NAME];
        if (!isset($this->idPermissionGroupCache[$shoppingListPermissionGroupName])) {
            $shoppingListPermissionGroupQuery = new SpyShoppingListPermissionGroupQuery();
            $idPermissionGroup = $shoppingListPermissionGroupQuery
                ->select([SpyShoppingListPermissionGroupTableMap::COL_ID_SHOPPING_LIST_PERMISSION_GROUP])
                ->findOneByName($shoppingListPermissionGroupName);

            if (!$idPermissionGroup) {
                throw new EntityNotFoundException(sprintf('Could not find shopping list permission group by name "%s"', $shoppingListPermissionGroupName));
            }

            $this->idPermissionGroupCache[$shoppingListPermissionGroupName] = $idPermissionGroup;
        }

        $dataSet[ShoppingListCompanyUserDataSetInterface::ID_PERMISSION_GROUP] = $this->idPermissionGroupCache[$shoppingListPermissionGroupName];
    }
}
