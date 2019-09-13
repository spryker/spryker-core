<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Dependency\Facade;

class PriceProductScheduleGuiToProductFacadeBridge implements PriceProductScheduleGuiToProductFacadeInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param int $idConcrete
     *
     * @return int|null
     */
    public function findProductAbstractIdByConcreteId(int $idConcrete): ?int
    {
        return $this->productFacade->findProductAbstractIdByConcreteId($idConcrete);
    }
}
