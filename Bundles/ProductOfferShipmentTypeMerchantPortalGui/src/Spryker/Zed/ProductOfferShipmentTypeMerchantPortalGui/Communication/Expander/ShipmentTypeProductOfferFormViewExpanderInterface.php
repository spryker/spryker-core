<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\ProductOfferFormViewCollectionTransfer;
use Symfony\Component\Form\FormView;

interface ShipmentTypeProductOfferFormViewExpanderInterface
{
    /**
     * @param \Symfony\Component\Form\FormView $formView
     * @param \Generated\Shared\Transfer\ProductOfferFormViewCollectionTransfer $productOfferFormViewCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferFormViewCollectionTransfer
     */
    public function expand(
        FormView $formView,
        ProductOfferFormViewCollectionTransfer $productOfferFormViewCollectionTransfer
    ): ProductOfferFormViewCollectionTransfer;
}
