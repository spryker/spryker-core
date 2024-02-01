<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Locale;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\Locale\Dependency\Client\LocaleToStoreClientInterface;
use Spryker\Glue\Locale\Negotiator\LanguageNegotiator;
use Spryker\Glue\Locale\Negotiator\LanguageNegotiatorInterface;
use Spryker\Service\Locale\LocaleServiceInterface;

/**
 * @method \Spryker\Client\Locale\LocaleClientInterface getClient()
 * @method \Spryker\Service\Locale\LocaleServiceInterface getService()
 */
class LocaleFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\Locale\Negotiator\LanguageNegotiatorInterface
     */
    public function createLanguageNegotiator(): LanguageNegotiatorInterface
    {
        return new LanguageNegotiator(
            $this->getClient(),
            $this->getLocaleService(),
            $this->getStoreClient(),
        );
    }

    /**
     * @return \Spryker\Glue\Locale\Dependency\Client\LocaleToStoreClientInterface
     */
    public function getStoreClient(): LocaleToStoreClientInterface
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Service\Locale\LocaleServiceInterface
     */
    public function getLocaleService(): LocaleServiceInterface
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::SERVICE_LOCALE);
    }
}
