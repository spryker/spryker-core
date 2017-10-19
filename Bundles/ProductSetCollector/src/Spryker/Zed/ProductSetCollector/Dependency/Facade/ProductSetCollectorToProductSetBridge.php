<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetCollector\Dependency\Facade;

class ProductSetCollectorToProductSetBridge implements ProductSetCollectorToProductSetInterface
{
    /**
     * @var \Spryker\Zed\ProductSet\Business\ProductSetFacadeInterface
     */
    protected $productSetFacade;

    /**
     * @param \Spryker\Zed\ProductSet\Business\ProductSetFacadeInterface $productSetFacade
     */
    public function __construct($productSetFacade)
    {
        $this->productSetFacade = $productSetFacade;
    }

    /**
     * @param int $idProductSet
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getCombinedProductSetImageSets($idProductSet, $idLocale)
    {
        return $this->productSetFacade->getCombinedProductSetImageSets($idProductSet, $idLocale);
    }
}
