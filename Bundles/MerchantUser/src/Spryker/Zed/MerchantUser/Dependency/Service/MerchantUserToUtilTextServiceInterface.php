<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Dependency\Service;

interface MerchantUserToUtilTextServiceInterface
{
    /**
     * Specification:
     * - Generates random string for given length value.
     *
     * @api
     *
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString($length);
}
