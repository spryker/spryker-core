<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Response;

class QuoteRequestsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_QUOTE_REQUESTS = 'quote-requests';
    public const RESOURCE_QUOTE_REQUEST_CANCEL = 'quote-request-cancel';

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
    public const RESPONSE_CODE_CART_IS_EMPTY = '4503';
    public const RESPONSE_CODE_QUOTE_REQUEST_WRONG_STATUS = '4504';
    public const RESPONSE_CODE_QUOTE_REQUEST_CONCURRENT_CUSTOMERS = '4505';
    public const RESPONSE_CODE_QUOTE_REQUEST_VALIDATION = '4506';

    public const RESPONSE_DETAIL_QUOTE_REQUEST_NOT_FOUND = 'Quote request not found.';
    public const RESPONSE_DETAIL_QUOTE_REQUEST_REFERENCE_MISSING = 'Quote request reference is required.';
    public const RESPONSE_DETAIL_CART_IS_EMPTY = 'Cart is empty.';
    public const RESPONSE_DETAIL_QUOTE_REQUEST_WRONG_STATUS = 'Wrong Quote Request status for this operation.';
    public const RESPONSE_DETAIL_QUOTE_REQUEST_CONCURRENT_CUSTOMERS = 'Quote Request could not be updated due to parallel-customer interaction.';
    public const RESPONSE_DETAIL_QUOTE_REQUEST_VALIDATION = 'Something went wrong.';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestTerminator::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestTerminator::GLOSSARY_KEY_CONCURRENT_CUSTOMERS
     */
    protected const GLOSSARY_KEY_CONCURRENT_CUSTOMERS = 'quote_request.update.validation.concurrent';

    /**
     * Specification:
     * - Contains mapping from possible `MessageTransfer.value` to Glue error.
     *
     * @api
     *
     * @return mixed[][]
     */
    public function getErrorMapping(): array
    {
        return [
            static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_QUOTE_REQUEST_WRONG_STATUS,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_QUOTE_REQUEST_WRONG_STATUS,
            ],
            static::GLOSSARY_KEY_CONCURRENT_CUSTOMERS => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_QUOTE_REQUEST_CONCURRENT_CUSTOMERS,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_QUOTE_REQUEST_CONCURRENT_CUSTOMERS,
            ],
        ];
    }
}
