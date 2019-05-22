<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Dependency\QueryContainer;

class ProductBundleToAvailabilityQueryContainerBridge implements ProductBundleToAvailabilityQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface $queryContainer
     */
    public function __construct($queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $sku
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityQuery
     */
    public function querySpyAvailabilityBySku($sku, $idStore)
    {
        if (method_exists($this->queryContainer, 'queryAvailabilityBySkuAndIdStore')) {
            return $this->queryContainer->queryAvailabilityBySkuAndIdStore($sku, $idStore);
        }

        return $this->queryContainer->querySpyAvailabilityBySku($sku);
    }
}
