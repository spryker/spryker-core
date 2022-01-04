<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Url\Dependency\Client;

interface UrlToLocaleClientInterface
{
    /**
     * @return string
     */
    public function getCurrentLanguage(): string;

    /**
     * @return array<string>
     */
    public function getLocales(): array;
}
