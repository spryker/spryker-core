<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Communication\Table;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AssignedCustomerTable extends AbstractCustomerTable
{

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config = parent::configure($config);

        $config->setUrl(sprintf('assigned-customer-table?id-user=%d', $this->userTransfer->getIdUser()));

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
            '<input class="%s js-customer-checkbox" type="checkbox" checked name="customer[]" value="%s" data-info="%s" />',
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
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function prepareQuery()
    {
        $query = $this->customerUserConnectorGuiToCustomerQueryContainerBridge
            ->queryCustomers()
                ->addAnd(
                    SpyCustomerTableMap::COL_FK_USER,
                    $this->userTransfer->getIdUser(),
                    Criteria::EQUAL
                )
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, static::COL_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, static::COL_LAST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_EMAIL, static::COL_EMAIL)
            ->withColumn(SpyCustomerTableMap::COL_GENDER, static::COL_GENDER);

        return $query;
    }

}
