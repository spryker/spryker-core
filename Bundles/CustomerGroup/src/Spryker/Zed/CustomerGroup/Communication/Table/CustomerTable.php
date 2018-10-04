<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Table;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\CustomerGroup\Persistence\Map\SpyCustomerGroupToCustomerTableMap;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CustomerTable extends AbstractTable
{
    public const ACTIONS = 'Actions';

    public const COL_FK_CUSTOMER = 'fk_customer';
    public const COL_FIRST_NAME = 'first_name';
    public const COL_LAST_NAME = 'last_name';
    public const COL_GENDER = 'gender';
    public const COL_EMAIL = 'email';

    public const GENDER_MAP = [
        0 => 'Male',
        1 => 'Female',
    ];

    /**
     * @var \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface
     */
    protected $customerGroupQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    protected $customerGroupTransfer;

    /**
     * @param \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface $customerQueryContainer
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     */
    public function __construct(CustomerGroupQueryContainerInterface $customerQueryContainer, CustomerGroupTransfer $customerGroupTransfer)
    {
        $this->customerGroupQueryContainer = $customerQueryContainer;
        $this->customerGroupTransfer = $customerGroupTransfer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_FK_CUSTOMER => '#',
            static::COL_FIRST_NAME => 'First Name',
            static::COL_LAST_NAME => 'Last Name',
            static::COL_GENDER => 'Gender',
            static::ACTIONS => self::ACTIONS,
        ]);

        $config->addRawColumn(self::ACTIONS);

        $config->setSortable([
            static::COL_FK_CUSTOMER,
            static::COL_FIRST_NAME,
            static::COL_LAST_NAME,
            static::COL_GENDER,
        ]);

        $config->setUrl(sprintf('table?id-customer-group=%d', $this->customerGroupTransfer->getIdCustomerGroup()));

        $config->setSearchable([
            SpyCustomerGroupToCustomerTableMap::COL_FK_CUSTOMER,
            SpyCustomerTableMap::COL_EMAIL,
            SpyCustomerTableMap::COL_FIRST_NAME,
            SpyCustomerTableMap::COL_LAST_NAME,
            SpyCustomerTableMap::COL_GENDER,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->prepareQuery();

        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomer[] $customerCollection */
        $customerCollection = $this->runQuery($query, $config, true);

        if ($customerCollection->count() < 1) {
            return [];
        }

        return $this->mapCustomerGroupCollection($customerCollection);
    }

    /**
     * @param \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomer $customerGroupToCustomerEntity
     *
     * @return string
     */
    protected function buildLinks(SpyCustomerGroupToCustomer $customerGroupToCustomerEntity)
    {
        $buttons = [];
        $buttons[] = $this->generateViewButton(
            sprintf('/customer/view?id-customer=%d', $customerGroupToCustomerEntity->getFkCustomer()),
            'View'
        );

        return implode(' ', $buttons);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomer[] $customersCollection
     *
     * @return array
     */
    protected function mapCustomerGroupCollection(ObjectCollection $customersCollection)
    {
        $customersList = [];

        foreach ($customersCollection as $customerGroupToCustomerEntity) {
            $customersList[] = $this->mapCustomerListRow($customerGroupToCustomerEntity);
        }

        return $customersList;
    }

    /**
     * @param \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomer $customerGroupToCustomerEntity
     *
     * @return array
     */
    protected function mapCustomerListRow(SpyCustomerGroupToCustomer $customerGroupToCustomerEntity)
    {
        $customerRow = $customerGroupToCustomerEntity->toArray();

        $customerRow[static::ACTIONS] = $this->buildLinks($customerGroupToCustomerEntity);
        $customerRow[static::COL_GENDER] = $this->getGender($customerRow);

        return $customerRow;
    }

    /**
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery
     */
    protected function prepareQuery()
    {
        $query = $this->customerGroupQueryContainer
            ->queryCustomerGroupToCustomerByFkCustomerGroup($this->customerGroupTransfer->getIdCustomerGroup())
            ->leftJoinCustomer()
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, static::COL_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, static::COL_LAST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_EMAIL, static::COL_EMAIL)
            ->withColumn(SpyCustomerTableMap::COL_GENDER, static::COL_GENDER);

        return $query;
    }

    /**
     * @param array $customerRow
     *
     * @return string
     */
    protected function getGender(array $customerRow)
    {
        if (!isset($customerRow[static::COL_GENDER])) {
            return 'n/a';
        }

        return self::GENDER_MAP[$customerRow[static::COL_GENDER]];
    }
}
