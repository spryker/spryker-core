<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage\Storage;

interface PriceProductMerchantRelationshipKeyGeneratorInterface
{
    /**
     * @param string $resourceName
     * @param int $idProduct
     * @param int $idCompanyBusinessUnit
     *
     * @return string
     */
    public function generateKey(string $resourceName, int $idProduct, int $idCompanyBusinessUnit): string;
}
