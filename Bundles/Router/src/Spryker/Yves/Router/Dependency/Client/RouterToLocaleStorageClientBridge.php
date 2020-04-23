<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Dependency\Client;

class RouterToLocaleStorageClientBridge implements RouterToLocaleStorageClientInterface
{
    /**
     * @var \Spryker\Client\LocaleStorage\LocaleStorageClientInterface
     */
    protected $localeStorageClient;

    /**
     * @param \Spryker\Client\LocaleStorage\LocaleStorageClientInterface $localeStorageClient
     */
    public function __construct($localeStorageClient)
    {
        $this->localeStorageClient = $localeStorageClient;
    }

    /**
     * @param string $storeName
     *
     * @return string[]
     */
    public function getLanguagesForStore(string $storeName): array
    {
        return $this->localeStorageClient->getLanguagesForStore($storeName);
    }
}
