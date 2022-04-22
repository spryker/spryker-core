<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Plugin\PriceProduct;

use ArrayObject;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductValidatorPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\PriceProductMerchantRelationshipMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\PriceProductMerchantRelationshipMerchantPortalGuiConfig getConfig()
 */
class MerchantRelationshipVolumePriceProductValidatorPlugin extends AbstractPlugin implements PriceProductValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates volume prices against having volume price for price with merchant relationship.
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validate(ArrayObject $priceProductTransfers): ValidationResponseTransfer
    {
        return $this->getFactory()
            ->createMerchantRelationshipVolumePriceProductValidator()
            ->validate($priceProductTransfers);
    }
}
