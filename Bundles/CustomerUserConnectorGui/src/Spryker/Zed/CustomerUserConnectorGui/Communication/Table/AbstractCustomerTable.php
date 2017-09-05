<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Communication\Table;

use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

abstract class AbstractCustomerTable extends AbstractTable
{

    const COL_FK_CUSTOMER = 'fk_customer';
    const COL_FIRST_NAME = 'first_name';
    const COL_LAST_NAME = 'last_name';
    const COL_GENDER = 'gender';
    const COL_EMAIL = 'email';

    const GENDER_MAP = [
        0 => 'Male',
        1 => 'Female',
    ];

    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\UserTransfer
     */
    protected $userTransfer;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $customerQueryContainer
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     */
    public function __construct(CustomerQueryContainerInterface $customerQueryContainer, UserTransfer $userTransfer)
    {
        $this->customerQueryContainer = $customerQueryContainer;
        $this->userTransfer = $userTransfer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyCustomerTableMap::COL_ID_CUSTOMER => 'ID',
            static::COL_FIRST_NAME => 'First Name',
            static::COL_LAST_NAME => 'Last Name',
            static::COL_GENDER => 'Gender',
            '#' => '#',
        ]);

        $config->setSortable([
            static::COL_FIRST_NAME,
            static::COL_LAST_NAME,
            static::COL_GENDER,
        ]);

        $config->setRawColumns([
            '#',
        ]);

        $config->setSearchable([
            SpyCustomerTableMap::COL_FIRST_NAME,
            SpyCustomerTableMap::COL_LAST_NAME,
            SpyCustomerTableMap::COL_GENDER,
        ]);

        return $config;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return string
     */
    protected function getSelectCheckboxColumn(SpyCustomer $customerEntity)
    {
        return sprintf(
            '<input class="%s js-abstract-product-checkbox" type="checkbox" name="customer[]" value="%s" data-info="%s" />',
            'js-item-checkbox',
            $customerEntity->getIdCustomer(),
            htmlspecialchars(json_encode([
                'idCustomer' => $customerEntity->getIdCustomer(),
                'firstname' => $customerEntity->getFirstName(),
                'lastname' => $customerEntity->getLastName(),
                'gender' => $customerEntity->getGender(),
            ]))
        );
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
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return array
     */
    protected function getRow(SpyCustomer $customerEntity)
    {
        return [
            '#' => $this->getSelectCheckboxColumn($customerEntity),
            SpyCustomerTableMap::COL_ID_CUSTOMER => $customerEntity->getIdCustomer(),
            static::COL_FIRST_NAME => $customerEntity->getFirstName(),
            static::COL_LAST_NAME => $customerEntity->getLastName(),
            static::COL_GENDER => $customerEntity->getGender(),
        ];
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->prepareQuery();

        $customerCollection = $this->runQuery($query, $config, true);

        $data = $this->buildResultData($customerCollection);

        return $data;
    }

    /**
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    abstract protected function prepareQuery();

}
