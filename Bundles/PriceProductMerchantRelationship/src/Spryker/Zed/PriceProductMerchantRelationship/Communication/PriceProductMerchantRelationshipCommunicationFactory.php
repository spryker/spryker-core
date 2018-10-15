<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductMerchantRelationship\Communication\Form\DataProvider\MerchantPriceDimensionFormDataProvider;
use Spryker\Zed\PriceProductMerchantRelationship\Communication\Form\MerchantPriceDimensionForm;
use Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToMerchantRelationshipFacadeInterface;
use Spryker\Zed\PriceProductMerchantRelationship\PriceProductMerchantRelationshipDependencyProvider;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig getConfig()
 */
class PriceProductMerchantRelationshipCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createMerchantPriceDimensionForm(): FormTypeInterface
    {
        return new MerchantPriceDimensionForm();
    }
    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationship\Communication\Form\DataProvider\MerchantPriceDimensionFormDataProvider
     */
    public function createMerchantPriceDimensionFormDataProvider(): MerchantPriceDimensionFormDataProvider
    {
        return new MerchantPriceDimensionFormDataProvider(
            $this->getMerchantRelationshipFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToMerchantRelationshipFacadeInterface
     */
    public function getMerchantRelationshipFacade(): PriceProductMerchantRelationshipToMerchantRelationshipFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipDependencyProvider::FACADE_MERCHANT_RELATIONSHIP);
    }
}
