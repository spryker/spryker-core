<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\UrlStorage\Dependency\Client;

interface UrlStorageToLocaleClientInterface
{
    /**
     * @return string
     */
    public function getCurrentLocale();
}
