<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Communication\Plugin\PriceProduct;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionConcreteSaverPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Business\PriceProductMerchantRelationshipFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Communication\PriceProductMerchantRelationshipCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig getConfig()
 */
class MerchantRelationshipPriceDimensionConcreteWriterPlugin extends AbstractPlugin implements PriceDimensionConcreteSaverPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function savePrice(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        return $this->getFacade()->savePriceProductMerchantRelationship($priceProductTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string
    {
        return PriceProductMerchantRelationshipConfig::PRICE_DIMENSION_MERCHANT_RELATIONSHIP;
    }
}
