<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig getConfig()
 * @method \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementFactory getFactory()
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

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addProductOffersParameter($productViewTransfer);
        $this->addProductParameter($productViewTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SspProductOfferPriceWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SspServiceManagement/views/product-offer-price-widget/product-offer-price-widget.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
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

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function addProductParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter(static::PARAMETER_PRODUCT, $productViewTransfer);
    }
}
