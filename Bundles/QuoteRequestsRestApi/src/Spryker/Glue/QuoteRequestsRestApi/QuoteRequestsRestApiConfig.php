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
    /**
     * @var string
     */
    public const RESOURCE_QUOTE_REQUESTS = 'quote-requests';

    /**
     * @var string
     */
    public const RESOURCE_QUOTE_REQUEST_CANCEL = 'quote-request-cancel';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestTerminator::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestTerminator::GLOSSARY_KEY_CONCURRENT_CUSTOMERS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_CONCURRENT_CUSTOMERS = 'quote_request.update.validation.concurrent';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestWriter::GLOSSARY_KEY_QUOTE_REQUEST_COMPANY_USER_NOT_FOUND
     *
     * @var string
     */
    public const GLOSSARY_KEY_QUOTE_REQUEST_COMPANY_USER_NOT_FOUND = 'quote_request.validation.error.company_user_not_found';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestWriter::GLOSSARY_KEY_QUOTE_REQUEST_CART_IS_EMPTY
     *
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_CART_IS_EMPTY = 'quote_request.validation.error.cart_is_empty';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND
     *
     * @var string
     */
    public const ERROR_IDENTIFIER_CART_NOT_FOUND = 'ERROR_IDENTIFIER_CART_NOT_FOUND';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND
     *
     * @var string
     */
    public const RESPONSE_CODE_CART_NOT_FOUND = '101';

    /**
     * @var string
     */
    public const RESPONSE_CODE_QUOTE_REQUEST_NOT_FOUND = '4501';

    /**
     * @var string
     */
    public const RESPONSE_CODE_COMPANY_USER_NOT_FOUND = '1404';

    /**
     * @var string
     */
    public const RESPONSE_CODE_QUOTE_REQUEST_WRONG_STATUS = '4504';

    /**
     * @var string
     */
    public const RESPONSE_CODE_QUOTE_REQUEST_CONCURRENT_CUSTOMERS = '4505';

    /**
     * @var string
     */
    public const RESPONSE_CODE_QUOTE_REQUEST_REFERENCE_MISSING = '4502';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CART_IS_EMPTY = '4503';

    /**
     * @var string
     */
    public const RESPONSE_CODE_QUOTE_REQUEST_VALIDATION = '4506';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_CART_IS_EMPTY = 'Cart is empty.';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_QUOTE_REQUEST_NOT_FOUND = 'Quote request not found.';

    /**
     * @var string
     */
    public const EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND = 'Cart with given uuid not found.';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_COMPANY_USER_NOT_FOUND = 'Company user not found.';

    /**
     * @var string
     */
    public const RESPONSE_PROBLEM_CREATING_QUOTE_REQUEST_DESCRIPTION = 'There was a problem adding the quote request.';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_QUOTE_REQUEST_WRONG_STATUS = 'Wrong Quote Request status for this operation.';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_QUOTE_REQUEST_CONCURRENT_CUSTOMERS = 'Quote Request could not be updated due to parallel-customer interaction.';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_QUOTE_REQUEST_REFERENCE_MISSING = 'Quote request reference is required.';

    /**
     * Specification:
     * - Contains mapping from possible `MessageTransfer.value` to Glue error.
     * - Handle "Cart not found" error.
     * - Handle "Cart has wrong status" error.
     * - Handle "User not found" error.
     * - Handle use case, when several customers are trying to manage quote request in parallel.
     * - Handle unsuccessfull result.
     *
     * @api
     *
     * @return array<string, array<string, mixed>>
     */
    public function getErrorIdentifierToRestErrorMapping(): array
    {
        return [
            static::ERROR_IDENTIFIER_CART_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CART_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_QUOTE_REQUEST_COMPANY_USER_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_COMPANY_USER_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_COMPANY_USER_NOT_FOUND,
            ],
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
            static::GLOSSARY_KEY_QUOTE_REQUEST_CART_IS_EMPTY => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CART_IS_EMPTY,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_CART_IS_EMPTY,
            ],
        ];
    }
}
