<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DiscountPromotion\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\DiscountPromotion\Dependency\PromotionProductMapperPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\DiscountPromotion\DiscountPromotionFactory getFactory()
 */
class ProductPromotionMapperPlugin extends AbstractPlugin implements PromotionProductMapperPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer[]
     */
    public function mapPromotionItemsFromProductStorage(QuoteTransfer $quoteTransfer, Request $request)
    {
        return $this->getFactory()
            ->createPromotionProductMapper()
            ->mapPromotionItemsFromProductStorage($quoteTransfer, $request);
    }
}
