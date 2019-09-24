<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;
use Spryker\Zed\PriceProductMerchantRelationshipGui\Communication\Form\MerchantRelationshipPriceDimensionForm;
use Spryker\Zed\PriceProductMerchantRelationshipGui\Dependency\Facade\PriceProductMerchantRelationshipGuiToMerchantRelationshipFacadeInterface;

class MerchantRelationshipPriceDimensionFormDataProvider
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipGui\Dependency\Facade\PriceProductMerchantRelationshipGuiToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipGui\Dependency\Facade\PriceProductMerchantRelationshipGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     */
    public function __construct(PriceProductMerchantRelationshipGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade)
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
            MerchantRelationshipPriceDimensionForm::OPTION_VALUES_MERCHANT_RELATIONSHIP_CHOICES => $merchantRelationshipChoices,
        ];
    }

    /**
     * @return array
     */
    protected function prepareMerchantRelationshipChoices(): array
    {
        $choices = [];
        $merchantRelationshipTransfers = $this->merchantRelationshipFacade->getMerchantRelationshipCollection(new MerchantRelationshipFilterTransfer());

        foreach ($merchantRelationshipTransfers as $merchantRelationshipTransfer) {
            $choices[$merchantRelationshipTransfer->getMerchant()->getName()][$merchantRelationshipTransfer->getName()] = $merchantRelationshipTransfer->getIdMerchantRelationship();
        }

        return $choices;
    }
}
