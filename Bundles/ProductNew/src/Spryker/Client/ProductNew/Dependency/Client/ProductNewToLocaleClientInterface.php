<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductNew\Dependency\Client;

interface ProductNewToLocaleClientInterface
{
    /**
     * @return string
     */
    public function getCurrentLocale();
}
