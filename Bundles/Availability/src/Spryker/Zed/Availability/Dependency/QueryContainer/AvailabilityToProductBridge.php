<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Dependency\QueryContainer;

class AvailabilityToProductBridge implements AvailabilityToProductInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $queryContainer
     */
    public function __construct($queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductAbstract()
    {
        return $this->queryContainer->queryProductAbstract();
    }
}
