<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Business\Model;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\ShoppingList\Persistence\Map\SpyShoppingListTableMap;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUserQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroupQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ShoppingListPermissionWriterStep implements DataImportStepInterface
{
    /**
     * @see \Spryker\Shared\ShoppingList\ShoppingListConfig::PERMISSION_GROUP_READ_ONLY;
     * @see \Spryker\Shared\ShoppingList\ShoppingListConfig::PERMISSION_GROUP_FULL_ACCESS;
     */
    protected const PERMISSION_GROUPS = [
        'READ_ONLY',
        'FULL_ACCESS',
    ];

    /**
     * @var int[]
     */
    protected $idShoppingListCache = [];

    /**
     * @var int[]
     */
    protected $idCompanyUserCache = [];

    /**
     * @var int[]
     */
    protected $idCompanyCache = [];

    /**
     * @var int[]
     */
    protected $idCustomerCache = [];

    /**
     * @var int[]
     */
    protected $idShoppingListPermissionGroup = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idShoppingList = $this->getIdShoppingListByKey(
            $dataSet[ShoppingListPermissionDataSetInterface::COLUMN_SHOPPING_LIST_KEY]
        );

        $idCompanyUser = $this->getIdCompanyUser(
            $dataSet[ShoppingListPermissionDataSetInterface::COLUMN_COMPANY_KEY],
            $dataSet[ShoppingListPermissionDataSetInterface::COLUMN_CUSTOMER_REFERENCE]
        );

        $idShoppingListPermissionGroup = $this->getIdShoppingListPermissionGroupByName(
            $dataSet[ShoppingListPermissionDataSetInterface::COLUMN_ACCESS_LEVEL]
        );

        $shoppingListCompanyUserEntity = SpyShoppingListCompanyUserQuery::create()
            ->filterByFkShoppingList($idShoppingList)
            ->filterByFkCompanyUser($idCompanyUser)
            ->findOneOrCreate();

        $shoppingListCompanyUserEntity
            ->setFkShoppingListPermissionGroup($idShoppingListPermissionGroup)
            ->save();
    }

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

    /**
     * @param string $companyKey
     * @param string $customerReference
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCompanyUser(string $companyKey, string $customerReference): int
    {
        $companyUserKey = $companyKey . '_' . $customerReference;
        if (isset($this->idCompanyUserCache[$companyUserKey])) {
            return $this->idCompanyUserCache[$companyUserKey];
        }

        $idCompany = $this->getIdCompanyByKey($companyKey);
        $idCustomer = $this->getIdCustomerByReference($customerReference);

        $idCompanyUser = SpyCompanyUserQuery::create()
            ->filterByFkCompany($idCompany)
            ->filterByFkCustomer($idCustomer)
            ->select(SpyCompanyUserTableMap::COL_ID_COMPANY_USER)
            ->findOne();

        if (!$idCompanyUser) {
            throw new EntityNotFoundException(
                sprintf(
                    'Company user with customer reference "%s" was not found in company "%s".',
                    $customerReference,
                    $companyKey
                )
            );
        }

        $this->idCompanyUserCache[$companyUserKey] = $idCompanyUser;

        return $idCompanyUser;
    }

    /**
     * @param string $companyKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCompanyByKey(string $companyKey): int
    {
        if (isset($this->idCompanyCache[$companyKey])) {
            return $this->idCompanyCache[$companyKey];
        }

        $idCompany = SpyCompanyQuery::create()
            ->filterByKey($companyKey)
            ->select(SpyCompanyTableMap::COL_ID_COMPANY)
            ->findOne();

        if (!$idCompany) {
            throw new EntityNotFoundException(
                sprintf(
                    'Company with key "%s" was not found.',
                    $companyKey
                )
            );
        }

        $this->idCompanyCache[$companyKey] = $idCompany;

        return $idCompany;
    }

    /**
     * @param string $customerReference
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCustomerByReference(string $customerReference): int
    {
        if (isset($this->idCustomerCache[$customerReference])) {
            return $this->idCustomerCache[$customerReference];
        }

        $idCustomer = SpyCustomerQuery::create()
            ->filterByCustomerReference($customerReference)
            ->select(SpyCustomerTableMap::COL_ID_CUSTOMER)
            ->findOne();

        if (!$idCustomer) {
            throw new EntityNotFoundException(
                sprintf(
                    'Customer user with reference "%s" was not found.',
                    $customerReference
                )
            );
        }

        $this->idCompanyUserCache[$customerReference] = $idCustomer;

        return $idCustomer;
    }

    /**
     * @param string $shoppingListPermissionGroupName
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdShoppingListPermissionGroupByName(string $shoppingListPermissionGroupName): int
    {
        if (isset($this->idShoppingListPermissionGroup[$shoppingListPermissionGroupName])) {
            return $this->idShoppingListPermissionGroup[$shoppingListPermissionGroupName];
        }

        if (!in_array($shoppingListPermissionGroupName, static::PERMISSION_GROUPS)) {
            throw new EntityNotFoundException(
                sprintf('Shopping List Permission with name "%s" is incorrect.', $shoppingListPermissionGroupName)
            );
        }

        $shoppingListPermissionGroupEntity = SpyShoppingListPermissionGroupQuery::create()
            ->filterByName($shoppingListPermissionGroupName)
            ->findOneOrCreate();
        $shoppingListPermissionGroupEntity->save();

        $this->idShoppingListPermissionGroup[$shoppingListPermissionGroupName] = $shoppingListPermissionGroupEntity->getIdShoppingListPermissionGroup();

        return $this->idShoppingListPermissionGroup[$shoppingListPermissionGroupName];
    }
}
