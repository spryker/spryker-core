<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class QuoteRequestsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_QUOTE_REQUESTS = 'quote-requests';
    public const RESOURCE_QUOTE_REQUEST_CANCEL = 'quote-request-cancel';

    public const RESPONSE_CODE_ITEM_VALIDATION = '4501';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND
     */
    public const RESPONSE_CODE_CART_NOT_FOUND = '101';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND
     */
    public const RESPONSE_DETAIL_CART_NOT_FOUND = 'Cart with given uuid not found.';

    public const RESPONSE_CODE_QUOTE_REQUEST_NOT_FOUND = '4501';
    public const RESPONSE_CODE_QUOTE_REQUEST_REFERENCE_MISSING = '4502';

    public const RESPONSE_DETAIL_QUOTE_REQUEST_NOT_FOUND = 'Quote request not found.';
    public const RESPONSE_DETAIL_QUOTE_REQUEST_REFERENCE_MISSING = 'Quote request reference is required.';
}
