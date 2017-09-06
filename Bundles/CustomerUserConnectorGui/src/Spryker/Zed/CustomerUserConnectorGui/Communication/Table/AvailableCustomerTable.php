<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Communication\Table;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AvailableCustomerTable extends AbstractCustomerTable
{

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config = parent::configure($config);

        $config->setUrl(sprintf('available-customer-table?id-user=%d', $this->userTransfer->getIdUser()));

        return $config;
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
                    Criteria::NOT_EQUAL
                )
                ->addOr(
                    SpyCustomerTableMap::COL_FK_USER,
                    null,
                    Criteria::ISNULL
                )
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, static::COL_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, static::COL_LAST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_EMAIL, static::COL_EMAIL)
            ->withColumn(SpyCustomerTableMap::COL_GENDER, static::COL_GENDER);

        return $query;
    }

}
