<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface AvailabilityStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array $abstractProductIds
     *
     * @return \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorageQuery
     */
    public function queryAvailabilityStorageByProductAbstractIds(array $abstractProductIds);

    /**
     * @api
     *
     * @param array $availabilityAbstractIds
     *
     * @return \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorageQuery
     */
    public function queryAvailabilityStorageByAvailabilityAbstractIds(array $availabilityAbstractIds);

    /**
     * @api
     *
     * @param array $availabilityAbstractIds
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityAbstractWithRelationsByIds(array $availabilityAbstractIds);

    /**
     * @api
     *
     * @param array $abstractProductSkus
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityAbstractByAbstractProductSkus(array $abstractProductSkus);

    /**
     * @api
     *
     * @param array $abstractProductIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractWithProductByAbstractProductIds(array $abstractProductIds);

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract(): SpyProductAbstractQuery;
}
