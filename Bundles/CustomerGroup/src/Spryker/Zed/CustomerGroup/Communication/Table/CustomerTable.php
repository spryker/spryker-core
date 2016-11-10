<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Table;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\CustomerGroup\Persistence\Map\SpyCustomerGroupTableMap;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Library\DateFormatterInterface;
use Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CustomerTable extends AbstractTable
{

    const ACTIONS = 'Actions';

    const COL_ID_CUSTOMER = 'id_customer';
    const COL_FIRST_NAME = 'first_name';
    const COL_LAST_NAME = 'last_name';
    const COL_GENDER = 'gender';

    /**
     * @var \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface
     */
    protected $customerGroupQueryContainer;

    /**
     * @var CustomerGroupTransfer
     */
    protected $customerGroupTransfer;

    /**
     * @param \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface $customerQueryContainer
     * @param CustomerGroupTransfer $customerGroupTransfer
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
            self::COL_ID_CUSTOMER => '#',
            self::COL_FIRST_NAME => 'First Name',
            self::COL_LAST_NAME => 'Last Name',
            self::COL_GENDER => 'Gender',
            self::ACTIONS => self::ACTIONS,
        ]);

        $config->addRawColumn(self::ACTIONS);

        $config->setSortable([
            self::COL_ID_CUSTOMER,
            self::COL_FIRST_NAME,
            self::COL_LAST_NAME,
            self::COL_GENDER,
        ]);

        $config->setUrl('table');

        $config->setSearchable([
            SpyCustomerTableMap::COL_ID_CUSTOMER,
            //SpyCustomerTableMap::COL_EMAIL,
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
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer
     *
     * @return string
     */
    protected function buildLinks(SpyCustomer $customer)
    {
        $buttons = [];
        $buttons[] = $this->generateViewButton('/customer/view?id-customer=' . $customer->getIdCustomer(), 'View');
        $buttons[] = $this->generateRemoveButton('/customer-group/delete/customer?id-customer=' . $customer->getIdCustomer(), 'Edit');

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
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer
     *
     * @return array
     */
    protected function hydrateCustomerListRow(SpyCustomer $customer)
    {
        $customerRow = $customer->toArray();

        $customerRow[self::ACTIONS] = $this->buildLinks($customer);

        return $customerRow;
    }

    /**
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery
     */
    protected function prepareQuery()
    {
        $query = $this->customerGroupQueryContainer->queryCustomerGroupToCustomerByIdCustomerGroup()
            ->filterByFkCustomer($this->customerGroupTransfer->getIdCustomerGroup());

        return $query;
    }

}
