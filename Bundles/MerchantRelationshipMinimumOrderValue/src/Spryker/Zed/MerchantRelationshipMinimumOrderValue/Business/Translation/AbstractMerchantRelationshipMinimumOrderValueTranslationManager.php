<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToStoreFacadeInterface;

class AbstractMerchantRelationshipMinimumOrderValueTranslationManager
{
    protected const MINIMUM_ORDER_VALUE_GLOSSARY_PREFIX = 'merchant-relationship-minimum-order-value';
    protected const MINIMUM_ORDER_VALUE_GLOSSARY_MESSAGE = 'message';

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface $glossaryFacade,
        MerchantRelationshipMinimumOrderValueToStoreFacadeInterface $storeFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return string
     */
    protected function generateGlossaryKey(MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer): string
    {
        $this->assertRequired($merchantRelationshipMinimumOrderValueTransfer);

        return strtolower(implode(
            '.',
            [
                static::MINIMUM_ORDER_VALUE_GLOSSARY_PREFIX,
                $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue()->getMinimumOrderValueType()->getThresholdGroup(),
                $merchantRelationshipMinimumOrderValueTransfer->getStore()->getName(),
                $merchantRelationshipMinimumOrderValueTransfer->getCurrency()->getCode(),
                $merchantRelationshipMinimumOrderValueTransfer->getMerchantRelationship()->getIdMerchantRelationship(),
                static::MINIMUM_ORDER_VALUE_GLOSSARY_MESSAGE,
            ]
        ));
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function createLocaleTransfer(string $localeName): LocaleTransfer
    {
        return (new LocaleTransfer())
            ->setLocaleName($localeName);
    }

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return void
     */
    protected function assertRequired(MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer): void
    {
        $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue()
            ->requireMinimumOrderValueType();

        $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue()->getMinimumOrderValueType()
            ->requireThresholdGroup();

        $merchantRelationshipMinimumOrderValueTransfer->getStore()
            ->requireName();

        $merchantRelationshipMinimumOrderValueTransfer->getCurrency()
            ->requireCode();

        $merchantRelationshipMinimumOrderValueTransfer->getMerchantRelationship()
            ->requireIdMerchantRelationship();
    }
}
