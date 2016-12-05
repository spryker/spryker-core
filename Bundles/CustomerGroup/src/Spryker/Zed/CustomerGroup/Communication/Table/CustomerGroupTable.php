<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Table;

use Orm\Zed\CustomerGroup\Persistence\Map\SpyCustomerGroupTableMap;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Library\DateFormatterInterface;
use Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CustomerGroupTable extends AbstractTable
{

    const ACTIONS = 'Actions';

    const COL_ID_CUSTOMER_GROUP = 'id_customer_group';
    const COL_NAME = 'name';
    const COL_DESCRIPTION = 'description';
    const COL_CREATED_AT = 'created_at';

    /**
     * @var \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface
     */
    protected $customerGroupQueryContainer;

    /**
     * @var \Spryker\Shared\Library\DateFormatterInterface
     */
    protected $dateFormatter;

    /**
     * @param \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface $customerQueryContainer
     * @param \Spryker\Shared\Library\DateFormatterInterface $dateFormatter
     */
    public function __construct(CustomerGroupQueryContainerInterface $customerQueryContainer, DateFormatterInterface $dateFormatter)
    {
        $this->customerGroupQueryContainer = $customerQueryContainer;
        $this->dateFormatter = $dateFormatter;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            self::COL_ID_CUSTOMER_GROUP => '#',
            self::COL_NAME => 'Name',
            self::COL_DESCRIPTION => 'Description',
            self::COL_CREATED_AT => 'Created',
            self::ACTIONS => self::ACTIONS,
        ]);

        $config->addRawColumn(self::ACTIONS);

        $config->setSortable([
            self::COL_ID_CUSTOMER_GROUP,
            self::COL_NAME,
            self::COL_CREATED_AT,
        ]);

        $config->setUrl('table');

        $config->setSearchable([
            SpyCustomerGroupTableMap::COL_ID_CUSTOMER_GROUP,
            SpyCustomerGroupTableMap::COL_NAME,
            SpyCustomerGroupTableMap::COL_DESCRIPTION,
            SpyCustomerGroupTableMap::COL_CREATED_AT,
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

        $customerGroupCollection = $this->runQuery($query, $config, true);

        if ($customerGroupCollection->count() < 1) {
            return [];
        }

        return $this->formatCustomerGroupCollection($customerGroupCollection);
    }

    /**
     * @param \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup|null $customerGroup
     *
     * @return string
     */
    protected function buildLinks(SpyCustomerGroup $customerGroup = null)
    {
        if ($customerGroup === null) {
            return '';
        }

        $buttons = [];
        $buttons[] = $this->generateViewButton('/customer-group/view?id-customer-group=' . $customerGroup->getIdCustomerGroup(), 'View');
        $buttons[] = $this->generateEditButton('/customer-group/edit?id-customer-group=' . $customerGroup->getIdCustomerGroup(), 'Edit');

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
     * @param \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup $customerGroup
     *
     * @return array
     */
    protected function hydrateCustomerListRow(SpyCustomerGroup $customerGroup)
    {
        $customerRow = $customerGroup->toArray();

        $customerRow[self::COL_CREATED_AT] = $this->dateFormatter->dateTime($customerGroup->getCreatedAt());
        $customerRow[self::ACTIONS] = $this->buildLinks($customerGroup);

        return $customerRow;
    }

    /**
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery
     */
    protected function prepareQuery()
    {
        $query = $this->customerGroupQueryContainer->queryCustomerGroup();

        return $query;
    }

}
