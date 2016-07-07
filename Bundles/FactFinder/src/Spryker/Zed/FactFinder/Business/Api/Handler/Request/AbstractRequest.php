<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Handler\Request;

use FACTFinder\Adapter\AbstractAdapter;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\FactFinder\Business\Api\Converter\ConverterFactory;
use Spryker\Zed\FactFinder\Business\Api\FFConnector;
use Spryker\Zed\FactFinder\Business\Log\LoggerTrait;

abstract class AbstractRequest implements RequestInterface
{

    use LoggerTrait;

    const TRANSACTION_TYPE = null;

    /**
     * @var \Spryker\Zed\FactFinder\Business\Api\FFConnector
     */
    protected $ffConnector;

    /**
     * @var \Spryker\Zed\FactFinder\Business\Api\Converter\ConverterFactory
     */
    protected $converterFactory;

    /**
     * @param \Spryker\Zed\FactFinder\Business\Api\FFConnector $ffConnector
     * @param \Spryker\Zed\FactFinder\Business\Api\Converter\ConverterFactory $converterFactory
     */
    public function __construct(
        FFConnector $ffConnector,
        ConverterFactory $converterFactory
    )
    {
        $this->ffConnector = $ffConnector;
        $this->converterFactory = $converterFactory;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \FACTFinder\Adapter\AbstractAdapter $ffAdapter
     *
     * @return void
     */
    protected function logInfo(
        QuoteTransfer $quoteTransfer,
        AbstractAdapter $ffAdapter
    )
    {
        $context = [
            'transaction_type' => static::TRANSACTION_TYPE,

            'request_body' => (string)$request,

            'response_type' => $response->getResponseType(),
            'response_result_code' => $response->getResultCode(),
            'response_result_text' => $response->getResultText(),
            'response_reason_code' => $response->getReasonCode(),
            'response_reason_text' => $response->getReasonText(),
            'response_status_code' => $response->getStatusCode(),
            'response_status_text' => $response->getStatusText(),
            'response_custom_message' => $response->getCustomerMessage(),
        ];

        $this->getLogger()->info(static::TRANSACTION_TYPE, $context);
    }
    
}
