<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConfiguration;

use Codeception\Actor;
use Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration;
use Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfigurationQuery;
use Propel\Runtime\Collection\ObjectCollection;

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
 * @method \Spryker\Zed\SalesProductConfiguration\Business\SalesProductConfigurationFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesProductConfigurationBusinessTester extends Actor
{
    use _generated\SalesProductConfigurationBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureSalesOrderItemConfigurationDatabaseTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty(
            $this->getSpySalesOrderItemConfigurationQuery(),
        );
    }

    /**
     * @return \Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfigurationQuery
     */
    public function getSpySalesOrderItemConfigurationQuery(): SpySalesOrderItemConfigurationQuery
    {
        return SpySalesOrderItemConfigurationQuery::create();
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration
     */
    public function createSalesOrderItemConfiguration(int $idSalesOrderItem): SpySalesOrderItemConfiguration
    {
        $salesOrderItemConfigurationEntity = (new SpySalesOrderItemConfiguration())
            ->setFkSalesOrderItem($idSalesOrderItem)
            ->setConfiguratorKey('test-configurator-key');
        $salesOrderItemConfigurationEntity->save();

        return $salesOrderItemConfigurationEntity;
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration>
     */
    public function getSalesOrderItemConfigurationEntities(): ObjectCollection
    {
        return $this->getSpySalesOrderItemConfigurationQuery()->find();
    }
}
