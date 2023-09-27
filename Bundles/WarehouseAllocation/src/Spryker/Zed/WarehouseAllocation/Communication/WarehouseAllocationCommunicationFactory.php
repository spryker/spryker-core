<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\WarehouseAllocation\Communication\Mapper\OrderMapper;
use Spryker\Zed\WarehouseAllocation\Communication\Mapper\OrderMapperInterface;

/**
 * @method \Spryker\Zed\WarehouseAllocation\Business\WarehouseAllocationFacadeInterface getFacade()
 * @method \Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationRepositoryInterface getRepository()
 * @method \Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\WarehouseAllocation\WarehouseAllocationConfig getConfig()
 */
class WarehouseAllocationCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\WarehouseAllocation\Communication\Mapper\OrderMapperInterface
     */
    public function createOrderMapper(): OrderMapperInterface
    {
        return new OrderMapper();
    }
}
