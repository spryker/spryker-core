<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\LocaleStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\LocaleStorage\LanguageReader\LanguageReader;
use Spryker\Client\LocaleStorage\LanguageReader\LanguageReaderInterface;
use Spryker\Shared\Kernel\Store;

class LocaleStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\LocaleStorage\LanguageReader\LanguageReaderInterface
     */
    public function createLanguageReader(): LanguageReaderInterface
    {
        return new LanguageReader($this->getStore());
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(LocaleStorageDependencyProvider::STORE);
    }
}
