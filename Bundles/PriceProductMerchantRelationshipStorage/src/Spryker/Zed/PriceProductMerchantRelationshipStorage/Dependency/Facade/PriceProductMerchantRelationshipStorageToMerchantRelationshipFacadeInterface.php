<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade;

use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;

interface PriceProductMerchantRelationshipStorageToMerchantRelationshipFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipFilterTransfer|null $merchantRelationshipFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\MerchantRelationshipTransfer>
     */
    public function getMerchantRelationshipCollection(?MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer = null): array;
}
