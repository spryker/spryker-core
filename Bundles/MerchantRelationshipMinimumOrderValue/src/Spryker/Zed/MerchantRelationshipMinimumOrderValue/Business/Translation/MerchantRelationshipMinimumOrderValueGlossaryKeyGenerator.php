<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;

class MerchantRelationshipMinimumOrderValueGlossaryKeyGenerator implements MerchantRelationshipMinimumOrderValueGlossaryKeyGeneratorInterface
{
    protected const MINIMUM_ORDER_VALUE_GLOSSARY_PREFIX = 'merchant-relationship-minimum-order-value';
    protected const MINIMUM_ORDER_VALUE_GLOSSARY_MESSAGE = 'message';

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function assignMessageGlossaryKey(
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer {
        $this->assertRequired($merchantRelationshipMinimumOrderValueTransfer);

        $merchantRelationshipMinimumOrderValueTransfer->getThreshold()->setThresholdNotMetMessageGlossaryKey(
            $this->generateMessageGlossaryKey($merchantRelationshipMinimumOrderValueTransfer)
        );

        return $merchantRelationshipMinimumOrderValueTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return string
     */
    protected function generateMessageGlossaryKey(MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer): string
    {
        return strtolower(implode(
            '.',
            [
                static::MINIMUM_ORDER_VALUE_GLOSSARY_PREFIX,
                $merchantRelationshipMinimumOrderValueTransfer->getThreshold()->getMinimumOrderValueType()->getThresholdGroup(),
                $merchantRelationshipMinimumOrderValueTransfer->getStore()->getName(),
                $merchantRelationshipMinimumOrderValueTransfer->getCurrency()->getCode(),
                $merchantRelationshipMinimumOrderValueTransfer->getMerchantRelationship()->getMerchantRelationshipKey(),
                static::MINIMUM_ORDER_VALUE_GLOSSARY_MESSAGE,
            ]
        ));
    }

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return void
     */
    protected function assertRequired(MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer): void
    {
        $merchantRelationshipMinimumOrderValueTransfer->getThreshold()
            ->requireMinimumOrderValueType();

        $merchantRelationshipMinimumOrderValueTransfer->getThreshold()->getMinimumOrderValueType()
            ->requireThresholdGroup();

        $merchantRelationshipMinimumOrderValueTransfer->getStore()
            ->requireName();

        $merchantRelationshipMinimumOrderValueTransfer->getCurrency()
            ->requireCode();

        $merchantRelationshipMinimumOrderValueTransfer->getMerchantRelationship()
            ->requireIdMerchantRelationship();
    }
}
