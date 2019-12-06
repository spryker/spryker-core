<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade;

use ArrayObject;

class ConfigurableBundleStorageToProductImageFacadeBridge implements ConfigurableBundleStorageToProductImageFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface
     */
    protected $productImageFacade;

    /**
     * @param \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface $productImageFacade
     */
    public function __construct($productImageFacade)
    {
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductImageSetTransfer[] $productImageSetTransfers
     * @param string $localeName
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function resolveProductImageSetsForLocale(ArrayObject $productImageSetTransfers, string $localeName): ArrayObject
    {
        return $this->productImageFacade->resolveProductImageSetsForLocale($productImageSetTransfers, $localeName);
    }
}
