<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

use Orm\Zed\Availability\Persistence\Base\SpyAvailabilityQuery;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;

interface AvailabilityQueryContainerInterface
{

    /**
     * @param string $sku
     *
     * @return SpyAvailabilityQuery
     */
    public function querySpyAvailabilityBySku($sku);

    /**
     * @param string $abstractSku
     *
     * @return SpyAvailabilityAbstractQuery
     */
    public function querySpyAvailabilityAbstractByAbstractSku($abstractSku);

    /**
     * @param int $idAvailabilityAbstract
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityAbstractByIdAvailabilityAbstract($idAvailabilityAbstract);

    /**
     * @param int $idAvailabilityAbstract
     *
     * @return SpyAvailabilityQuery
     */
    public function querySumQuantityOfAvailabilityAbstract($idAvailabilityAbstract);

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function querySpyProductBySku($sku);
}
