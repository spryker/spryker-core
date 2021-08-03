<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultStoreDefaultLocaleNotFoundException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('Default locale not found for a default store.');
    }
}
