<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantShipment\Persistence\Mapper\MerchantShipmentMapper;

/**
 * @method \Spryker\Zed\MerchantShipment\MerchantShipmentConfig getConfig()
 * @method \Spryker\Zed\MerchantShipment\Persistence\MerchantShipmentRepositoryInterface getRepository()
 */
class MerchantShipmentPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @phpstan-return \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery<mixed>
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery
     */
    public function createSalesShipmentPropelQuery(): SpySalesShipmentQuery
    {
        return SpySalesShipmentQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantShipment\Persistence\Mapper\MerchantShipmentMapper
     */
    public function createMerchantShipmentMapper(): MerchantShipmentMapper
    {
        return new MerchantShipmentMapper();
    }
}
