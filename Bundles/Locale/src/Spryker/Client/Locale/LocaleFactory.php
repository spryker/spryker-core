<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Locale;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Locale\Dependency\Client\LocaleToStoreClientInterface;
use Spryker\Client\Locale\Reader\LanguageReader;
use Spryker\Client\Locale\Reader\LanguageReaderInterface;
use Spryker\Client\Locale\Reader\LocaleReader;
use Spryker\Client\Locale\Reader\LocaleReaderInterface;

class LocaleFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Locale\Reader\LanguageReaderInterface
     */
    public function createLanguageReader(): LanguageReaderInterface
    {
        return new LanguageReader();
    }

    /**
     * @return \Spryker\Client\Locale\Reader\LocaleReaderInterface
     */
    public function createLocaleReader(): LocaleReaderInterface
    {
        return new LocaleReader(
            $this->getStoreClient(),
            $this->createLanguageReader(),
        );
    }

    /**
     * @return string
     */
    public function getLocaleCurrent(): string
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::LOCALE_CURRENT);
    }

    /**
     * @return \Spryker\Client\Locale\Dependency\Client\LocaleToStoreClientInterface
     */
    public function getStoreClient(): LocaleToStoreClientInterface
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::CLIENT_STORE);
    }
}
