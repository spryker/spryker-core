<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\LocaleStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\LocaleStorage\LocaleStorageFactory getFactory()
 */
class LocaleStorageClient extends AbstractClient implements LocaleStorageClientInterface
{
    /**
     * @inheritDoc
     *
     * @api
     *
     * @param string $storeName
     *
     * @return array
     */
    public function getLanguagesForStore(string $storeName): array
    {
        return $this->getFactory()
            ->createLanguageReader()
            ->getLanguagesForStore($storeName);
    }
}
