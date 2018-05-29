<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductAlternativeGui\Business\ProductAlternativeGuiBusinessFactory getFactory()
 */
class ProductAlternativeGuiFacade extends AbstractFacade implements ProductAlternativeGuiFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $productName
     *
     * @return string[]
     */
    public function suggestProductNames(string $productName): array
    {
        return $this
            ->getFactory()
            ->createProductSuggester()
            ->suggestProductName($productName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $productSku
     *
     * @return string[]
     */
    public function suggestProductSkus(string $productSku): array
    {
        return $this
            ->getFactory()
            ->createProductSuggester()
            ->suggestProductSku($productSku);
    }
}
