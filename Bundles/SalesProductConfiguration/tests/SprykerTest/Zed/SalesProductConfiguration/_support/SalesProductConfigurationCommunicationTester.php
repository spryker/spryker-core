<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\SalesProductConfiguration;

use Codeception\Actor;
use Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration;
use Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfigurationQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
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
class SalesProductConfigurationCommunicationTester extends Actor
{
    use _generated\SalesProductConfigurationCommunicationTesterActions;

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
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\SalesProductConfiguration\Persistence\Base\SpySalesOrderItemConfiguration
     */
    public function findSalesOrderItemConfiguration(int $idSalesOrderItem): SpySalesOrderItemConfiguration
    {
        return $this->getSpySalesOrderItemConfigurationQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->findOne();
    }

    /**
     * @return \Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfigurationQuery
     */
    public function getSpySalesOrderItemConfigurationQuery(): SpySalesOrderItemConfigurationQuery
    {
        return SpySalesOrderItemConfigurationQuery::create();
    }
}
