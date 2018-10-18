<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Communication\Form\DataProvider;

use Spryker\Zed\PriceProductMerchantRelationship\Communication\Form\MerchantPriceDimensionForm;
use Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToMerchantRelationshipFacadeInterface;

class MerchantPriceDimensionFormDataProvider
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     */
    public function __construct(PriceProductMerchantRelationshipToMerchantRelationshipFacadeInterface $merchantRelationshipFacade)
    {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $merchantRelationshipChoices = $this->prepareMerchantRelationshipChoices();

        return [
            MerchantPriceDimensionForm::OPTION_VALUES_MERCHANT_RELATIONSHIP_CHOICES => $merchantRelationshipChoices,
        ];
    }

    /**
     * @return array
     */
    protected function prepareMerchantRelationshipChoices(): array
    {
        $choices = [];
        $merchantRelationships = $this->merchantRelationshipFacade->getMerchantRelationshipCollection();

        foreach ($merchantRelationships as $merchantRelationshipTransfer) {
            $choices[$merchantRelationshipTransfer->getMerchant()->getName()][$merchantRelationshipTransfer->getName()] = $merchantRelationshipTransfer->getIdMerchantRelationship();
        }

        return $choices;
    }
}
