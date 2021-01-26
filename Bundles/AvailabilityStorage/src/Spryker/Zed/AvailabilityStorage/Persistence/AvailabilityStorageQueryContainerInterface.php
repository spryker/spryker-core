<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Persistence;

use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface AvailabilityStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $abstractProductIds
     *
     * @return \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorageQuery
     */
    public function queryAvailabilityStorageByProductAbstractIds(array $abstractProductIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $availabilityAbstractIds
     *
     * @return \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorageQuery
     */
    public function queryAvailabilityStorageByAvailabilityAbstractIds(array $availabilityAbstractIds);

    /**
     * Specification:
     * - Returns a a query for the table `spy_availability_abstract` filtered by availability abstract ids.
     *
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $availabilityAbstractIds
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityByAvailabilityAbstractIds(array $availabilityAbstractIds): SpyAvailabilityAbstractQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $availabilityAbstractIds
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityAbstractWithRelationsByIds(array $availabilityAbstractIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $abstractProductSkus
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityAbstractByAbstractProductSkus(array $abstractProductSkus);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $abstractProductIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractWithProductByAbstractProductIds(array $abstractProductIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int[] $abstractProductIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractByIds(array $abstractProductIds): SpyProductAbstractQuery;
}
