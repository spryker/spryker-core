<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Price;

use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceFacadeInterface;

class PriceReaderWithCache implements PriceReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @var string
     */
    protected static $priceCache;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceFacadeInterface $priceFacade
     */
    public function __construct(ProductBundleToPriceFacadeInterface $priceFacade)
    {
        $this->priceFacade = $priceFacade;
    }

    /**
     * @return string
     */
    public function getNetPriceModeIdentifier(): string
    {
        if (static::$priceCache === null) {
            static::$priceCache = $this->priceFacade->getNetPriceModeIdentifier();
        }

        return static::$priceCache;
    }
}
