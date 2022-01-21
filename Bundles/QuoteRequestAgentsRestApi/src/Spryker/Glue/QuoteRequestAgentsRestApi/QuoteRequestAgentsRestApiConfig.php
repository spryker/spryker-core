<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestAgentsRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Response;

class QuoteRequestAgentsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_AGENT_QUOTE_REQUESTS = 'agent-quote-requests';

    /**
     * @var string
     */
    public const RESOURCE_AGENT_QUOTE_REQUEST_CANCEL = 'agent-quote-request-cancel';

    /**
     * @var string
     */
    public const RESOURCE_AGENT_QUOTE_REQUEST_REVISE = 'agent-quote-request-revise';

    /**
     * @var string
     */
    public const RESOURCE_AGENT_QUOTE_REQUEST_SEND_TO_CUSTOMER = 'agent-quote-request-send-to-customer';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestWriter::GLOSSARY_KEY_QUOTE_REQUEST_COMPANY_USER_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_COMPANY_USER_NOT_FOUND = 'quote_request.validation.error.company_user_not_found';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestTerminator::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @var string
     */
    public const RESPONSE_CODE_COMPANY_USER_NOT_FOUND = '1404';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_COMPANY_USER_NOT_FOUND = 'Company user not found';

    /**
     * @var string
     */
    public const RESPONSE_CODE_PROBLEM_CREATING_REQUEST_FOR_QUOTE_BY_AGENT = '4507';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_PROBLEM_CREATING_REQUEST_FOR_QUOTE_BY_AGENT = 'There was a problem adding the quote request for agent.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_QUOTE_REQUEST_NOT_FOUND = '4501';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_QUOTE_REQUEST_NOT_FOUND = 'Quote request not found.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_QUOTE_REQUEST_REFERENCE_MISSING = '4502';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_QUOTE_REQUEST_REFERENCE_MISSING = 'Quote request reference is required.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_QUOTE_REQUEST_WRONG_STATUS = '4504';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_QUOTE_REQUEST_WRONG_STATUS = 'Wrong Quote Request status for this operation.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_METADATA_DELIVERY_DATE_IS_INVALID = '4506';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_METADATA_DELIVERY_DATE_IS_INVALID = 'The date should be greater than the current date.';

    /**
     * Specification:
     * - Contains mapping from possible `MessageTransfer.value` to Glue error.
     * - Handle "Company user not found" error.
     * - Handle any unsuccessful response.
     *
     * @api
     *
     * @return array<string, array<string, mixed>>
     */
    public function getErrorIdentifierToRestErrorMapping(): array
    {
        return [
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
        ];
    }
}
