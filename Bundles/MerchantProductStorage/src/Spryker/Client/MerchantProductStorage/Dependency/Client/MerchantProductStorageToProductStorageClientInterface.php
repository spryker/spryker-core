<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage\Dependency\Client;

interface MerchantProductStorageToProductStorageClientInterface
{
    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array<mixed>
     */
    public function getProductAbstractStorageData($idProductAbstract, $localeName);
}
