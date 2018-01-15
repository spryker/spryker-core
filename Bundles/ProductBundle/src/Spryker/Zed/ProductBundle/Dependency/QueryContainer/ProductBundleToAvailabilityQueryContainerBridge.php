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
     * @return \Orm\Zed\Availability\Persistence\Base\SpyAvailabilityQuery
     */
    public function querySpyAvailabilityBySku($sku, $idStore)
    {
        return $this->queryContainer->querySpyAvailabilityBySku($sku, $idStore);
    }

    /**
     * @param int $idAvailabilityAbstract
     * @param int $idStore;
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityAbstractByIdAvailabilityAbstract($idAvailabilityAbstract, $idStore)
    {
        return $this->queryContainer->queryAvailabilityAbstractByIdAvailabilityAbstract($idAvailabilityAbstract, $idStore);
    }

    /**
     * @param int $idAvailabilityAbstract
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\Base\SpyAvailabilityQuery
     */
    public function querySumQuantityOfAvailabilityAbstract($idAvailabilityAbstract, $idStore)
    {
        return $this->queryContainer->querySumQuantityOfAvailabilityAbstract($idAvailabilityAbstract, $idStore);
    }
}
