<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOfferFormViewCollectionTransfer;
use Symfony\Component\Form\FormView;

/**
 * Provides `ProductOfferForm` Twig template expansion capabilities.
 *
 * Use this plugin interface for expanding `ProductOfferForm` Twig template with new content.
 */
interface ProductOfferFormViewExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `ProductOfferForm` Twig template with additional data.
     *
     * @api
     *
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
