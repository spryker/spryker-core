<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep;

use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUserQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShoppingListDataImport\Business\DataSet\ShoppingListCompanyUserDataSetInterface;

class ShoppingListCompanyUserWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $shoppingListCompanyUserEntity = $this->createShoppingListCompanyUserQuery()
            ->filterByFkShoppingList($dataSet[ShoppingListCompanyUserDataSetInterface::ID_SHOPPING_LIST])
            ->filterByFkCompanyUser($dataSet[ShoppingListCompanyUserDataSetInterface::ID_COMPANY_USER])
            ->findOneOrCreate();

        $shoppingListCompanyUserEntity
            ->setFkShoppingListPermissionGroup($dataSet[ShoppingListCompanyUserDataSetInterface::ID_PERMISSION_GROUP])
            ->save();
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUserQuery
     */
    protected function createShoppingListCompanyUserQuery(): SpyShoppingListCompanyUserQuery
    {
        return SpyShoppingListCompanyUserQuery::create();
    }
}
