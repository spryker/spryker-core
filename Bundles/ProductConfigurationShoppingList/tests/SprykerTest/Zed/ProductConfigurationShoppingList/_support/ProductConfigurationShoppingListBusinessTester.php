<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationShoppingList;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use InvalidArgumentException;
use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListItemTableMap;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \Spryker\Zed\ProductConfigurationShoppingList\Business\ProductConfigurationShoppingListFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductConfigurationShoppingListBusinessTester extends Actor
{
    use _generated\ProductConfigurationShoppingListBusinessTesterActions;

    /**
     * @var string
     */
    protected const COLUMN_PRODUCT_CONFIGURATION_INSTACE_DATA = 'ProductConfigurationInstanceData';

    /**
     * @param array $data
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function encodeJson(array $data): string
    {
        $encodedString = $this->getLocator()->utilEncoding()->service()->encodeJson($data);
        if ($encodedString === null) {
            throw new InvalidArgumentException('Null value returned, invalid $data');
        }

        return $encodedString;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer|null $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function createShoppingListItem(?ProductConcreteTransfer $productConcreteTransfer = null): ShoppingListItemTransfer
    {
        $productConcreteTransfer = $productConcreteTransfer ?? $this->haveProduct();
        $customerTransfer = $this->haveCustomer();

        $companyTransfer = $this->haveCompany([CompanyTransfer::IS_ACTIVE => true]);
        $companyBusinessUnitTransfer = $this->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $companyUserTransfer = $this->haveCompanyUser([
            CompanyUserTransfer::IS_ACTIVE => true,
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);

        $shoppingListTransfer = $this->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
        ]);

        return $this->haveShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $productConcreteTransfer->getSku(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return string|null
     */
    public function findProductConfigurationData(ShoppingListItemTransfer $shoppingListItemTransfer): ?string
    {
        /** @phpstan-return string|null */
        return (SpyShoppingListItemQuery::create())
            ->filterByUuid($shoppingListItemTransfer->getUuid())
            ->select([SpyShoppingListItemTableMap::COL_PRODUCT_CONFIGURATION_INSTANCE_DATA])
            ->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function updateProductConfigurationData(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        (SpyShoppingListItemQuery::create())
            ->filterByIdShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem())
            ->update([static::COLUMN_PRODUCT_CONFIGURATION_INSTACE_DATA => $shoppingListItemTransfer->getProductConfigurationInstanceData()]);
    }
}
