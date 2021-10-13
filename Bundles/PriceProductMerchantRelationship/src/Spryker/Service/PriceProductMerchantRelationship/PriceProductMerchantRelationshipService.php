<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductMerchantRelationship;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\PriceProductMerchantRelationship\PriceProductMerchantRelationshipServiceFactory getFactory()
 */
class PriceProductMerchantRelationshipService extends AbstractService implements PriceProductMerchantRelationshipServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function filterPriceProductsByMerchantRelationship(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array
    {
        return $this->getFactory()
            ->createMerchantRelationshipPriceProductFilter()
            ->filterPriceProductsByMerchantRelationship($priceProductTransfers, $priceProductFilterTransfer);
    }
}
