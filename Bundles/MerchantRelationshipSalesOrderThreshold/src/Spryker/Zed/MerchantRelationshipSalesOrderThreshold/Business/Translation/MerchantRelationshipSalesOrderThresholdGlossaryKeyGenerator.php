<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;

class MerchantRelationshipSalesOrderThresholdGlossaryKeyGenerator implements MerchantRelationshipSalesOrderThresholdGlossaryKeyGeneratorInterface
{
    /**
     * @var string
     */
    protected const SALES_ORDER_THRESHOLD_GLOSSARY_PREFIX = 'merchant-relationship-threshold';
    /**
     * @var string
     */
    protected const SALES_ORDER_THRESHOLD_GLOSSARY_MESSAGE = 'message';
    /**
     * @var string
     */
    protected const MERCHANT_RELATIONSHIP_IDENTIFIER_PREFIX = 'mr-';

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function assignMessageGlossaryKey(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        $this->assertRequiredTransferAttributes($merchantRelationshipSalesOrderThresholdTransfer);

        $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->setMessageGlossaryKey(
            $this->generateMessageGlossaryKey($merchantRelationshipSalesOrderThresholdTransfer)
        );

        return $merchantRelationshipSalesOrderThresholdTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return string
     */
    protected function generateMessageGlossaryKey(MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer): string
    {
        $merchantRelationshipSalesOrderThresholdTransfer->getMerchantRelationship()
            ->requireIdMerchantRelationship();

        return strtolower(implode(
            '.',
            [
                static::SALES_ORDER_THRESHOLD_GLOSSARY_PREFIX,
                $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()->getThresholdGroup(),
                $merchantRelationshipSalesOrderThresholdTransfer->getStore()->getName(),
                $merchantRelationshipSalesOrderThresholdTransfer->getCurrency()->getCode(),
                $this->getMerchantRelationshipIdentifier($merchantRelationshipSalesOrderThresholdTransfer->getMerchantRelationship()),
                static::SALES_ORDER_THRESHOLD_GLOSSARY_MESSAGE,
            ]
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return string
     */
    protected function getMerchantRelationshipIdentifier(MerchantRelationshipTransfer $merchantRelationshipTransfer): string
    {
        return sprintf('%s%d', static::MERCHANT_RELATIONSHIP_IDENTIFIER_PREFIX, $merchantRelationshipTransfer->getIdMerchantRelationship());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return void
     */
    protected function assertRequiredTransferAttributes(MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer): void
    {
        $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()
            ->requireSalesOrderThresholdType()
            ->getSalesOrderThresholdType()
            ->requireThresholdGroup();

        $merchantRelationshipSalesOrderThresholdTransfer->getStore()
            ->requireName();

        $merchantRelationshipSalesOrderThresholdTransfer->getCurrency()
            ->requireCode();

        $merchantRelationshipSalesOrderThresholdTransfer->getMerchantRelationship()
            ->requireIdMerchantRelationship();
    }
}
