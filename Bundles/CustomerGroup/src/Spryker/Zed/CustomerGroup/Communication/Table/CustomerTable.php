<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Table;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Orm\Zed\CustomerGroup\Persistence\Map\SpyCustomerGroupToCustomerTableMap;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Url\Url;
use Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CustomerTable extends AbstractTable
{

    const ACTIONS = 'Actions';

    const COL_FK_CUSTOMER = 'fk_customer';
    const COL_FIRST_NAME = 'first_name';
    const COL_LAST_NAME = 'last_name';
    const COL_GENDER = 'gender';
    const GENDER_MAPPER = [
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
            self::COL_FK_CUSTOMER => '#',
            self::COL_FIRST_NAME => 'First Name',
            self::COL_LAST_NAME => 'Last Name',
            self::COL_GENDER => 'Gender',
            self::ACTIONS => self::ACTIONS,
        ]);

        $config->addRawColumn(self::ACTIONS);

        $config->setSortable([
            self::COL_FK_CUSTOMER,
            self::COL_FIRST_NAME,
            self::COL_LAST_NAME,
            self::COL_GENDER,
        ]);

        $config->setUrl('table?id-customer-group=' . $this->customerGroupTransfer->getIdCustomerGroup());

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
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->prepareQuery();

        $customerCollection = $this->runQuery($query, $config, true);

        if ($customerCollection->count() < 1) {
            return [];
        }

        return $this->formatCustomerGroupCollection($customerCollection);
    }

    /**
     * @param \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomer $customerGroupToCustomer
     *
     * @return string
     */
    protected function buildLinks(SpyCustomerGroupToCustomer $customerGroupToCustomer)
    {
        $buttons = [];
        $buttons[] = $this->generateViewButton('/customer/view?id-customer=' . $customerGroupToCustomer->getFkCustomer(), 'View');

        $url = Url::generate('/customer-group/delete/customer', [
            'id-customer-group' => $customerGroupToCustomer->getFkCustomerGroup(),
            'id-customer' => $customerGroupToCustomer->getFkCustomer(),
        ]);
        $buttons[] = $this->generateRemoveButton($url, 'Remove');

        return implode(' ', $buttons);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $customersCollection
     *
     * @return array
     */
    protected function formatCustomerGroupCollection(ObjectCollection $customersCollection)
    {
        $customersList = [];

        foreach ($customersCollection as $customer) {
            $customersList[] = $this->hydrateCustomerListRow($customer);
        }

        return $customersList;
    }

    /**
     * @param \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomer $customerGroupToCustomer
     *
     * @return array
     */
    protected function hydrateCustomerListRow(SpyCustomerGroupToCustomer $customerGroupToCustomer)
    {
        $customerRow = $customerGroupToCustomer->toArray();

        $customerRow[self::ACTIONS] = $this->buildLinks($customerGroupToCustomer);
        $customerRow['gender'] = self::GENDER_MAPPER[$customerRow['gender']];

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
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, 'first_name')
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, 'last_name')
            ->withColumn(SpyCustomerTableMap::COL_EMAIL, 'email')
            ->withColumn(SpyCustomerTableMap::COL_GENDER, 'gender');

        return $query;
    }

}
