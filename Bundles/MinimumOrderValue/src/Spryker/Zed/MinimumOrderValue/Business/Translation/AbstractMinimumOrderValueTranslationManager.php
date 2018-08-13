<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToGlossaryFacadeInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToStoreFacadeInterface;

class AbstractMinimumOrderValueTranslationManager
{
    protected const MINIMUM_ORDER_VALUE_GLOSSARY_PREFIX = 'minimum-order-value';
    protected const MINIMUM_ORDER_VALUE_GLOSSARY_MESSAGE = 'message';

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        MinimumOrderValueToGlossaryFacadeInterface $glossaryFacade,
        MinimumOrderValueToStoreFacadeInterface $storeFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @return string
     */
    protected function generateGlossaryKey(GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer): string
    {
        $this->assertRequired($globalMinimumOrderValueTransfer);

        return strtolower(implode(
            '.',
            [
                static::MINIMUM_ORDER_VALUE_GLOSSARY_PREFIX,
                $globalMinimumOrderValueTransfer->getMinimumOrderValue()->getMinimumOrderValueType()->getThresholdGroup(),
                $globalMinimumOrderValueTransfer->getStore()->getName(),
                $globalMinimumOrderValueTransfer->getCurrency()->getCode(),
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
     * @param \Spryker\Zed\MinimumOrderValue\Business\Translation\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @return void
     */
    protected function assertRequired(GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer): void
    {
        $globalMinimumOrderValueTransfer->getMinimumOrderValue()
            ->requireMinimumOrderValueType();

        $globalMinimumOrderValueTransfer->getMinimumOrderValue()->getMinimumOrderValueType()
            ->requireThresholdGroup();

        $globalMinimumOrderValueTransfer->getStore()
            ->requireName();

        $globalMinimumOrderValueTransfer->getCurrency()
            ->requireCode();
    }
}
