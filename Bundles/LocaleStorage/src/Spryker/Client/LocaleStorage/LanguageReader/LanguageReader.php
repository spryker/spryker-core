<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\LocaleStorage\LanguageReader;

use Spryker\Shared\Kernel\Store;

class LanguageReader implements LanguageReaderInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * @param string $storeName
     *
     * @return string[]
     */
    public function getLanguagesForStore(string $storeName): array
    {
        $locales = $this->store->getLocalesPerStore($storeName);

        return array_keys($locales);
    }
}
