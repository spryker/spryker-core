<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantFile\Business\Exception;

use Exception;

class MerchantFileNotFoundException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Merchant file not found';
}
