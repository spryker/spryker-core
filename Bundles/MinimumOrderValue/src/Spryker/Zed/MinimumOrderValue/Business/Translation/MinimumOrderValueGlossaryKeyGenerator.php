<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;

class MinimumOrderValueGlossaryKeyGenerator implements MinimumOrderValueGlossaryKeyGeneratorInterface
{
    protected const MINIMUM_ORDER_VALUE_GLOSSARY_PREFIX = 'minimum-order-value';
    protected const MINIMUM_ORDER_VALUE_GLOSSARY_MESSAGE = 'message';

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function assignMessageGlossaryKey(
        MinimumOrderValueTransfer $minimumOrderValueTransfer
    ): MinimumOrderValueTransfer {
        $this->assertRequired($minimumOrderValueTransfer);

        $minimumOrderValueTransfer->getThreshold()->setThresholdNotMetMessageGlossaryKey(
            $this->generateMessageGlossaryKey($minimumOrderValueTransfer)
        );

        return $minimumOrderValueTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return string
     */
    protected function generateMessageGlossaryKey(MinimumOrderValueTransfer $minimumOrderValueTransfer): string
    {
        return strtolower(implode(
            '.',
            [
                static::MINIMUM_ORDER_VALUE_GLOSSARY_PREFIX,
                $minimumOrderValueTransfer->getThreshold()->getMinimumOrderValueType()->getThresholdGroup(),
                $minimumOrderValueTransfer->getStore()->getName(),
                $minimumOrderValueTransfer->getCurrency()->getCode(),
                static::MINIMUM_ORDER_VALUE_GLOSSARY_MESSAGE,
            ]
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return void
     */
    protected function assertRequired(MinimumOrderValueTransfer $minimumOrderValueTransfer): void
    {
        $minimumOrderValueTransfer->getThreshold()
            ->requireMinimumOrderValueType();

        $minimumOrderValueTransfer->getThreshold()->getMinimumOrderValueType()
            ->requireThresholdGroup();

        $minimumOrderValueTransfer->getStore()
            ->requireName();

        $minimumOrderValueTransfer->getCurrency()
            ->requireCode();
    }
}
