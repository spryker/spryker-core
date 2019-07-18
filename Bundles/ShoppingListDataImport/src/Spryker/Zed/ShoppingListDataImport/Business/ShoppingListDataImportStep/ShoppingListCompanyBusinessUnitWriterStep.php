<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep;

use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnitQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShoppingListDataImport\Business\DataSet\ShoppingListCompanyBusinessUnitDataSetInterface;

class ShoppingListCompanyBusinessUnitWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $shoppingListCompanyUserEntity = $this->createShoppingListCompanyBusinessUnitQuery()
            ->filterByFkShoppingList($dataSet[ShoppingListCompanyBusinessUnitDataSetInterface::ID_SHOPPING_LIST])
            ->filterByFkCompanyBusinessUnit($dataSet[ShoppingListCompanyBusinessUnitDataSetInterface::ID_COMPANY_BUSINESS_UNIT])
            ->findOneOrCreate();

        $shoppingListCompanyUserEntity
            ->setFkShoppingListPermissionGroup($dataSet[ShoppingListCompanyBusinessUnitDataSetInterface::ID_PERMISSION_GROUP])
            ->save();
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnitQuery
     */
    protected function createShoppingListCompanyBusinessUnitQuery(): SpyShoppingListCompanyBusinessUnitQuery
    {
        return SpyShoppingListCompanyBusinessUnitQuery::create();
    }
}
