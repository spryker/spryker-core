<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\ProductOfferFormViewCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferFormViewTransfer;
use Symfony\Component\Form\FormView;
use Twig\Environment;

class ShipmentTypeProductOfferFormViewExpander implements ShipmentTypeProductOfferFormViewExpanderInterface
{
    /**
     * @var string
     */
    protected const TEMPLATE_PATH_SHIPMENT_TYPE_CARD = '@ProductOfferShipmentTypeMerchantPortalGui/Partials/ProductOfferForm/shipment-type-card.twig';

    /**
     * @var \Twig\Environment
     */
    protected Environment $twigEnvironment;

    /**
     * @param \Twig\Environment $twigEnvironment
     */
    public function __construct(Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * @param \Symfony\Component\Form\FormView $formView
     * @param \Generated\Shared\Transfer\ProductOfferFormViewCollectionTransfer $productOfferFormViewCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferFormViewCollectionTransfer
     */
    public function expand(
        FormView $formView,
        ProductOfferFormViewCollectionTransfer $productOfferFormViewCollectionTransfer
    ): ProductOfferFormViewCollectionTransfer {
        $content = $this->twigEnvironment->render(
            static::TEMPLATE_PATH_SHIPMENT_TYPE_CARD,
            [
                'form' => $formView,
            ],
        );

        $productOfferFormViewCollectionTransfer->addProductOfferFormView(
            (new ProductOfferFormViewTransfer())->setContent($content),
        );

        return $productOfferFormViewCollectionTransfer;
    }
}
