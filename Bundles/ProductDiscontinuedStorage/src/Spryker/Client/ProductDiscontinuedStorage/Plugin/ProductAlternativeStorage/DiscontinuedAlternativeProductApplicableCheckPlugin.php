<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Plugin\ProductAlternativeStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductAlternativeStorageExtension\Dependency\Plugin\AlternativeProductApplicableCheckPluginInterface;

/**
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageClientInterface getClient()
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageFactory getFactory()
 */
class DiscontinuedAlternativeProductApplicableCheckPlugin extends AbstractPlugin implements AlternativeProductApplicableCheckPluginInterface
{
    /**
     * Specification:
     *  - Checks if product is discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function check(ProductViewTransfer $productViewTransfer): bool
    {
        return (bool)$this->getClient()->findProductDiscontinuedStorage(
            $productViewTransfer->getSku(),
            $this->getFactory()->getLocaleClient()->getCurrentLocale()
        );
    }
}
