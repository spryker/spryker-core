<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Exception;

use Exception;

class DefaultMerchantStockNotFoundException extends Exception
{
    /**
     * @param int $idMerchant
     */
    public function __construct(int $idMerchant)
    {
        parent::__construct($this->buildMessage($idMerchant));
    }

    /**
     * @param int $idMerchant
     *
     * @return string
     */
    protected function buildMessage(int $idMerchant): string
    {
        return sprintf('Default Merchant stock not found by Merchant ID `%s`', $idMerchant);
    }
}
