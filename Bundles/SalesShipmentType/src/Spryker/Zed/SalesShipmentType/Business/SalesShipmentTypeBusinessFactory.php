<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesShipmentType\Business\Creator\SalesShipmentTypeCreator;
use Spryker\Zed\SalesShipmentType\Business\Creator\SalesShipmentTypeCreatorInterface;
use Spryker\Zed\SalesShipmentType\Business\Grouper\SalesShipmentTypeGrouper;
use Spryker\Zed\SalesShipmentType\Business\Grouper\SalesShipmentTypeGrouperInterface;
use Spryker\Zed\SalesShipmentType\Business\Mapper\SalesShipmentTypeMapper;
use Spryker\Zed\SalesShipmentType\Business\Mapper\SalesShipmentTypeMapperInterface;
use Spryker\Zed\SalesShipmentType\Business\Updater\SalesShipmentUpdater;
use Spryker\Zed\SalesShipmentType\Business\Updater\SalesShipmentUpdaterInterface;

/**
 * @method \Spryker\Zed\SalesShipmentType\SalesShipmentTypeConfig getConfig()
 * @method \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeRepositoryInterface getRepository()
 */
class SalesShipmentTypeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesShipmentType\Business\Updater\SalesShipmentUpdaterInterface
     */
    public function createSalesShipmentUpdater(): SalesShipmentUpdaterInterface
    {
        return new SalesShipmentUpdater(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createSalesShipmentTypeCreator(),
            $this->createSalesShipmentTypeGrouper(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesShipmentType\Business\Creator\SalesShipmentTypeCreatorInterface
     */
    public function createSalesShipmentTypeCreator(): SalesShipmentTypeCreatorInterface
    {
        return new SalesShipmentTypeCreator(
            $this->getEntityManager(),
            $this->createSalesShipmentTypeMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesShipmentType\Business\Mapper\SalesShipmentTypeMapperInterface
     */
    public function createSalesShipmentTypeMapper(): SalesShipmentTypeMapperInterface
    {
        return new SalesShipmentTypeMapper();
    }

    /**
     * @return \Spryker\Zed\SalesShipmentType\Business\Grouper\SalesShipmentTypeGrouperInterface
     */
    public function createSalesShipmentTypeGrouper(): SalesShipmentTypeGrouperInterface
    {
        return new SalesShipmentTypeGrouper();
    }
}
