<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Dependency\Facade;

class AvailabilityToProductBridge implements AvailabilityToProductInterface
{

    /**
     * @var \Spryker\Zed\Product\Business\ProductFacade
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacade $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @api
     *
     * @param string $productConcreteSku
     *
     * @return int
     */
    public function getAbstractSkuFromProductConcrete($productConcreteSku)
    {
        return $this->productFacade->getAbstractSkuFromProductConcrete($productConcreteSku);
    }

}
