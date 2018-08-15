<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;

class MinimumOrderValueGlossaryKeyGenerator implements MinimumOrderValueGlossaryKeyGeneratorInterface
{
    protected const MINIMUM_ORDER_VALUE_GLOSSARY_PREFIX = 'minimum-order-value';
    protected const MINIMUM_ORDER_VALUE_GLOSSARY_MESSAGE = 'message';

    /**
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    public function assignMessageGlossaryKey(
        GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
    ): GlobalMinimumOrderValueTransfer {
        $this->assertRequired($globalMinimumOrderValueTransfer);

        $globalMinimumOrderValueTransfer->getMinimumOrderValue()->setMessageGlossaryKey(
            $this->generateMessageGlossaryKey($globalMinimumOrderValueTransfer)
        );

        return $globalMinimumOrderValueTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @return string
     */
    protected function generateMessageGlossaryKey(GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer): string
    {
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
