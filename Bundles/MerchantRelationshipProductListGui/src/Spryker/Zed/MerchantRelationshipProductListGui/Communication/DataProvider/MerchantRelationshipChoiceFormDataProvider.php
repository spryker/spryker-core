<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\DataProvider;

use Spryker\Zed\MerchantRelationshipProductListGui\Communication\Form\MerchantRelationshipChoiceFormType;
use Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface;

class MerchantRelationshipChoiceFormDataProvider
{
    protected const PATTERN_MERCHANT_RELATIONSHIP_KEY = '%s - %s';

    /**
     * @var \Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     */
    public function __construct(MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade)
    {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            MerchantRelationshipChoiceFormType::OPTION_VALUES_MERCHANT_RELATIONSHIP_CHOICES => $this->getMerchantRelationshipChoices(),
        ];
    }

    /**
     * @return array
     */
    protected function getMerchantRelationshipChoices(): array
    {
        $result = [];

        foreach ($this->merchantRelationshipFacade->getMerchantRelationshipCollection() as $merchantRelationshipTransfer) {
            $merchantRelationshipKey = sprintf(
                static::PATTERN_MERCHANT_RELATIONSHIP_KEY,
                $merchantRelationshipTransfer->getMerchant()->getName(),
                $merchantRelationshipTransfer->getOwnerCompanyBusinessUnit()->getName()
            );
            $result[$merchantRelationshipKey] = $merchantRelationshipTransfer->getIdMerchantRelationship();
        }

        return $result;
    }
}
