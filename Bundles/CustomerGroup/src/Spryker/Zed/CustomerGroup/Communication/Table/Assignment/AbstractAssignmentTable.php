<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Table\Assignment;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CustomerGroup\Dependency\Service\CustomerGroupToUtilEncodingInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

abstract class AbstractAssignmentTable extends AbstractTable
{
    public const PARAM_ID_CUSTOMER_GROUP = 'id-customer-group';

    public const COL_SELECT_CHECKBOX = 'select-checkbox';
    public const COL_CUSTOMER_ID = SpyCustomerTableMap::COL_ID_CUSTOMER;
    public const COL_CUSTOMER_EMAIL = SpyCustomerTableMap::COL_EMAIL;
    public const COL_CUSTOMER_FIRST_NAME = SpyCustomerTableMap::COL_FIRST_NAME;
    public const COL_CUSTOMER_LAST_NAME = SpyCustomerTableMap::COL_LAST_NAME;

    /**
     * @var \Spryker\Zed\CustomerGroup\Dependency\Service\CustomerGroupToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @var \Spryker\Zed\CustomerGroup\Communication\Table\Assignment\AssignmentCustomerQueryBuilderInterface
     */
    protected $tableQueryBuilder;

    /**
     * @var int
     */
    protected $idCustomerGroup;

    /**
     * @param \Spryker\Zed\CustomerGroup\Communication\Table\Assignment\AssignmentCustomerQueryBuilderInterface $tableQueryBuilder
     * @param \Spryker\Zed\CustomerGroup\Dependency\Service\CustomerGroupToUtilEncodingInterface $utilEncoding
     * @param int $idCustomerGroup
     */
    public function __construct(
        AssignmentCustomerQueryBuilderInterface $tableQueryBuilder,
        CustomerGroupToUtilEncodingInterface $utilEncoding,
        $idCustomerGroup
    ) {
        $this->tableQueryBuilder = $tableQueryBuilder;
        $this->utilEncoding = $utilEncoding;
        $this->idCustomerGroup = $idCustomerGroup;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->configureHeader($config);
        $this->configureRawColumns($config);
        $this->configureSorting($config);
        $this->configureSearching($config);
        $this->configureUrl($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureHeader(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_SELECT_CHECKBOX => 'Select',
            static::COL_CUSTOMER_ID => 'ID',
            static::COL_CUSTOMER_EMAIL => 'Email',
            static::COL_CUSTOMER_FIRST_NAME => 'First Name',
            static::COL_CUSTOMER_LAST_NAME => 'Last Name',
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureRawColumns(TableConfiguration $config)
    {
        $config->setRawColumns([
            static::COL_SELECT_CHECKBOX,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSorting(TableConfiguration $config)
    {
        $config->setDefaultSortField(
            static::COL_CUSTOMER_ID,
            TableConfiguration::SORT_ASC
        );

        $config->setSortable([
            static::COL_CUSTOMER_ID,
            static::COL_CUSTOMER_EMAIL,
            static::COL_CUSTOMER_FIRST_NAME,
            static::COL_CUSTOMER_LAST_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSearching(TableConfiguration $config)
    {
        $config->setSearchable([
            static::COL_CUSTOMER_ID,
            static::COL_CUSTOMER_EMAIL,
            static::COL_CUSTOMER_FIRST_NAME,
            static::COL_CUSTOMER_LAST_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureUrl(TableConfiguration $config)
    {
        $config->setUrl(sprintf(
            '%s?%s=%s',
            $this->defaultUrl,
            static::PARAM_ID_CUSTOMER_GROUP,
            $this->idCustomerGroup
        ));
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return array
     */
    protected function getRow(SpyCustomer $customerEntity)
    {
        return [
            static::COL_SELECT_CHECKBOX => $this->getSelectCheckboxColumn($customerEntity),
            static::COL_CUSTOMER_ID => $customerEntity->getIdCustomer(),
            static::COL_CUSTOMER_EMAIL => $customerEntity->getEmail(),
            static::COL_CUSTOMER_FIRST_NAME => $customerEntity->getFirstName(),
            static::COL_CUSTOMER_LAST_NAME => $customerEntity->getLastName(),
        ];
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer[]|\Propel\Runtime\Collection\ObjectCollection $customerEntities
     *
     * @return array
     */
    protected function buildResultData(ObjectCollection $customerEntities)
    {
        $tableRows = [];

        foreach ($customerEntities as $customerEntity) {
            $tableRows[] = $this->getRow($customerEntity);
        }

        return $tableRows;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->getQuery();

        /** @var \Orm\Zed\Customer\Persistence\SpyCustomer[]|\Propel\Runtime\Collection\ObjectCollection $customerEntities */
        $customerEntities = $this->runQuery($query, $config, true);
        $rows = $this->buildResultData($customerEntities);

        return $rows;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return string
     */
    protected function getSelectCheckboxColumn(SpyCustomer $customerEntity)
    {
        return sprintf(
            '<input class="%s" type="checkbox" name="customer[]" value="%s" %s data-info="%s"/>',
            'js-item-checkbox',
            $customerEntity->getIdCustomer(),
            $this->getCheckboxCheckedAttribute(),
            htmlspecialchars($this->utilEncoding->encodeJson([
                'id' => $customerEntity->getIdCustomer(),
                'email' => $customerEntity->getEmail(),
                'firstName' => $customerEntity->getFirstName(),
                'lastName' => $customerEntity->getLastName(),
            ]))
        );
    }

    /**
     * @return string
     */
    abstract protected function getCheckboxCheckedAttribute();

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    abstract protected function getQuery();
}
