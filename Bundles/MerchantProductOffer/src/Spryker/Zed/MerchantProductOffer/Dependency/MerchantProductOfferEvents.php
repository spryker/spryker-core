<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Dependency;

interface MerchantProductOfferEvents
{
    /**
     * Specification
     * - This events will be used for merchant product offer publishing.
     *
     * @api
     */
    public const MERCHANT_PRODUCT_OFFER_PUBLISH = 'MerchantProductOffer.product_offer.publish';

    /**
     * Specification
     * - This events will be used for merchant product offer un-publishing.
     *
     * @api
     */
    public const MERCHANT_PRODUCT_OFFER_UNPUBLISH = 'MerchantProductOffer.product_offer.unpublish';
}
