<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesOrderThresholdsRestApi\Business\Expander\QuoteExpander;
use Spryker\Zed\SalesOrderThresholdsRestApi\Business\Expander\QuoteExpanderInterface;
use Spryker\Zed\SalesOrderThresholdsRestApi\Business\Validator\SalesOrderThresholdValidator;
use Spryker\Zed\SalesOrderThresholdsRestApi\Business\Validator\SalesOrderThresholdValidatorInterface;
use Spryker\Zed\SalesOrderThresholdsRestApi\Dependency\Facade\SalesOrderThresholdsRestApiToSalesOrderThresholdFacadeInterface;
use Spryker\Zed\SalesOrderThresholdsRestApi\SalesOrderThresholdsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\SalesOrderThresholdsRestApi\SalesOrderThresholdsRestApiConfig getConfig()
 */
class SalesOrderThresholdsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesOrderThresholdsRestApi\Business\Expander\QuoteExpanderInterface
     */
    public function createQuoteExpander(): QuoteExpanderInterface
    {
        return new QuoteExpander(
            $this->getSalesOrderThresholdFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdsRestApi\Business\Validator\SalesOrderThresholdValidatorInterface
     */
    public function createSalesOrderThresholdValidator(): SalesOrderThresholdValidatorInterface
    {
        return new SalesOrderThresholdValidator(
            $this->getSalesOrderThresholdFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdsRestApi\Dependency\Facade\SalesOrderThresholdsRestApiToSalesOrderThresholdFacadeInterface
     */
    public function getSalesOrderThresholdFacade(): SalesOrderThresholdsRestApiToSalesOrderThresholdFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdsRestApiDependencyProvider::FACADE_SALES_ORDER_THRESHOLD);
    }
}
