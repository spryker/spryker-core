<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business;

use Generated\Shared\Transfer\ProductConcreteTransfer;
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
     * @param string $searchName
     *
     * @return string[]
     */
    public function suggestProduct(string $searchName): array
    {
        return $this
            ->getFactory()
            ->createProductSuggester()
            ->suggestProduct($searchName);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductAlternatives(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this
            ->getFactory()
            ->createProductAlternativeCreator()
            ->persistProductAlternatives($productConcreteTransfer);
    }
}
