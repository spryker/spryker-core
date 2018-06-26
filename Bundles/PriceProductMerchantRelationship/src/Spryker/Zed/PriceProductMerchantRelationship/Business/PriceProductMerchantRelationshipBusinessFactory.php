<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProductMerchantRelationship\Business\Model\MerchantRelationshipPriceWriter;
use Spryker\Zed\PriceProductMerchantRelationship\Business\Model\MerchantRelationshipPriceWriterInterface;
use Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductMerchantRelationship\PriceProductMerchantRelationshipDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipRepositoryInterface getRepository()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig getConfig()
 */
class PriceProductMerchantRelationshipBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationship\Business\Model\MerchantRelationshipPriceWriterInterface
     */
    public function createMerchantRelationshipPriceWriter(): MerchantRelationshipPriceWriterInterface
    {
        return new MerchantRelationshipPriceWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getPriceProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductMerchantRelationshipToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipDependencyProvider::FACADE_PRICE_PRODUCT);
    }
}
