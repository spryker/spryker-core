<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Quote\Session\QuoteSession;

class QuoteFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Quote\Session\QuoteSession
     */
    public function createSession()
    {
        return new QuoteSession(
            $this->getSessionClient(),
            $this->getCurrencyPlugin()
        );
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    protected function getSessionClient()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Client\Currency\Plugin\CurrencyPluginInterface
     */
    protected function getCurrencyPlugin()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::CURRENCY_PLUGIN);
    }
}
