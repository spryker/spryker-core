<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Plugin\ProductMerchantPortalGui;

use Generated\Shared\Transfer\PriceProductTableCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductTableFilterPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\PriceProductMerchantRelationshipMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\PriceProductMerchantRelationshipMerchantPortalGuiConfig getConfig()
 */
class MerchantRelationshipPriceProductTableFilterPlugin extends AbstractPlugin implements PriceProductTableFilterPluginInterface
{
 /**
  * {@inheritDoc}
  * - Filters price product transfers by merchant relationship.
  *
  * @api
  *
  * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
  * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
  *
  * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
  */
    public function filter(
        array $priceProductTransfers,
        PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
    ): array {
        return $this->getFactory()
            ->createMerchantRelationshipPriceProductFilter()
            ->filterPriceProductCollection(
                $priceProductTransfers,
                $priceProductTableCriteriaTransfer,
            );
    }
}
