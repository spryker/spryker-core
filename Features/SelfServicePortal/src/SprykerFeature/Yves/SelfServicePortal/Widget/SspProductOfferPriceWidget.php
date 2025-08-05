<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspProductOfferPriceWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT_OFFERS = 'productOffers';

    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT = 'product';

    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addProductOffersParameter($productViewTransfer);
        $this->addProductParameter($productViewTransfer);
    }

    public static function getName(): string
    {
        return 'SspProductOfferPriceWidget';
    }

    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/product-offer-price-widget/product-offer-price-widget.twig';
    }

    protected function addProductOffersParameter(ProductViewTransfer $productViewTransfer): void
    {
        $productOfferTransfers = $this->getFactory()
            ->createProductOfferReader()
            ->getProductOffers($productViewTransfer);

        $this->addParameter(
            static::PARAMETER_PRODUCT_OFFERS,
            $productOfferTransfers,
        );
    }

    protected function addProductParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter(static::PARAMETER_PRODUCT, $productViewTransfer);
    }
}
