<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Communication\Plugin\PriceProduct;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductStorePreDeletePluginInterface;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Business\PriceProductMerchantRelationshipFacade getFacade()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Communication\PriceProductMerchantRelationshipCommunicationFactory getFactory()
 */
class MerchantRelationshipPriceProductStorePreDeletePlugin extends AbstractPlugin implements PriceProductStorePreDeletePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idPriceProductStore
     *
     * @return void
     */
    public function preDelete(int $idPriceProductStore): void
    {
        $this->getFacade()
            ->deletePriceProductMerchantRelationshipByIdPriceProductStore($idPriceProductStore);
    }
}
