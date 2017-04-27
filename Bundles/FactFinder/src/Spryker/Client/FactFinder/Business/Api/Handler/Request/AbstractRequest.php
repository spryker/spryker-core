<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Handler\Request;

use Spryker\Client\FactFinder\Business\Api\Converter\ConverterFactory;
use Spryker\Client\FactFinder\Business\Api\FactFinderConnector;
use Spryker\Client\FactFinder\Business\Log\LoggerTrait;

abstract class AbstractRequest
{

    use LoggerTrait;

    const TRANSACTION_TYPE = null;

    /**
     * @var \Spryker\Client\FactFinder\Business\Api\FactFinderConnector
     */
    protected $factFinderConnector;

    /**
     * @var \Spryker\Client\FactFinder\Business\Api\Converter\ConverterFactory
     */
    protected $converterFactory;

    /**
     * @param \Spryker\Client\FactFinder\Business\Api\FactFinderConnector $factFinderConnector
     * @param \Spryker\Client\FactFinder\Business\Api\Converter\ConverterFactory $converterFactory
     */
    public function __construct(
        FactFinderConnector $factFinderConnector,
        ConverterFactory $converterFactory
    ) {

        $this->factFinderConnector = $factFinderConnector;
        $this->converterFactory = $converterFactory;
    }

}
