<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Handler\Request;

use FACTFinder\Adapter\AbstractAdapter;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\FactFinder\Business\Api\Converter\ConverterFactory;
use Spryker\Client\FactFinder\Business\Api\FactFinderConnector;
use Spryker\Client\FactFinder\Business\Log\LoggerTrait;

abstract class AbstractRequest implements RequestInterface
{

    use LoggerTrait;

    const TRANSACTION_TYPE = null;

    /**
     * @var \Spryker\Client\FactFinder\Business\Api\FactFinderConnector
     */
    protected $ffConnector;

    /**
     * @var \Spryker\Client\FactFinder\Business\Api\Converter\ConverterFactory
     */
    protected $converterFactory;

    /**
     * @param \Spryker\Client\FactFinder\Business\Api\FactFinderConnector $ffConnector
     * @param \Spryker\Client\FactFinder\Business\Api\Converter\ConverterFactory $converterFactory
     */
    public function __construct(
        FactFinderConnector $ffConnector,
        ConverterFactory $converterFactory
    ) {

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
    ) {

        $context = [
            'transaction_type' => static::TRANSACTION_TYPE,
        ];

        $this->getLogger()->info(static::TRANSACTION_TYPE, $context);
    }

}
