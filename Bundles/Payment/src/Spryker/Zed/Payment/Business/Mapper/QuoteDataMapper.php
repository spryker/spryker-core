<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * Maps allowed fields from QuoteTransfer and to array for using them in foreign payment authorizer
 */
class QuoteDataMapper implements QuoteDataMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<mixed> $quoteFieldsAllowedForSending
     *
     * @return array<mixed>
     */
    public function mapQuoteDataByAllowedFields(
        QuoteTransfer $quoteTransfer,
        array $quoteFieldsAllowedForSending
    ): array {
        return $this->mapTransferDataByAllowedFieldsRecursive(
            $quoteTransfer,
            $quoteFieldsAllowedForSending,
            [],
        );
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     * @param array<mixed> $allowedFields
     * @param array<mixed> $mappedData
     *
     * @return array<mixed>
     */
    protected function mapTransferDataByAllowedFieldsRecursive(
        AbstractTransfer $transfer,
        array $allowedFields,
        array $mappedData
    ): array {
        $camelCasedData = $transfer->toArrayNotRecursiveCamelCased();

        foreach ($allowedFields as $fieldName => $allowedData) {
            $fieldValue = $camelCasedData[$fieldName];

            if (is_array($allowedData) && $fieldValue === null) {
                continue;
            }

            if (is_array($allowedData) && $fieldValue instanceof AbstractTransfer) {
                $mappedData = $this->mapTransferDataByAllowedFieldsRecursive($fieldValue, $allowedData, $mappedData);

                continue;
            }

            if (is_array($allowedData) && $fieldValue instanceof ArrayObject) {
                foreach ($fieldValue as $transfer) {
                    $mappedData[$fieldName][] = $this->mapTransferDataByAllowedFieldsRecursive($transfer, $allowedData, []);
                }

                continue;
            }

            $mappedData[$allowedData] = $fieldValue;
        }

        return $mappedData;
    }
}
