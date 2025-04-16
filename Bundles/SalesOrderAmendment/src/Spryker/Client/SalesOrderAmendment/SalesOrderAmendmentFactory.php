<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesOrderAmendment;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SalesOrderAmendment\Checker\CurrentCurrencyIsoCodeChecker;
use Spryker\Client\SalesOrderAmendment\Checker\CurrentCurrencyIsoCodeCheckerInterface;
use Spryker\Client\SalesOrderAmendment\Checker\CurrentPriceModeChecker;
use Spryker\Client\SalesOrderAmendment\Checker\CurrentPriceModeCheckerInterface;
use Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToMessengerClientInterface;
use Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToQuoteClientInterface;

class SalesOrderAmendmentFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SalesOrderAmendment\Checker\CurrentCurrencyIsoCodeCheckerInterface
     */
    public function createCurrentCurrencyIsoCodeChecker(): CurrentCurrencyIsoCodeCheckerInterface
    {
        return new CurrentCurrencyIsoCodeChecker(
            $this->getQuoteClient(),
            $this->getMessengerClient(),
        );
    }

    /**
     * @return \Spryker\Client\SalesOrderAmendment\Checker\CurrentPriceModeCheckerInterface
     */
    public function createCurrentPriceModeChecker(): CurrentPriceModeCheckerInterface
    {
        return new CurrentPriceModeChecker($this->getMessengerClient());
    }

    /**
     * @return \Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToQuoteClientInterface
     */
    public function getQuoteClient(): SalesOrderAmendmentToQuoteClientInterface
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToMessengerClientInterface
     */
    public function getMessengerClient(): SalesOrderAmendmentToMessengerClientInterface
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::CLIENT_MESSENGER);
    }
}
