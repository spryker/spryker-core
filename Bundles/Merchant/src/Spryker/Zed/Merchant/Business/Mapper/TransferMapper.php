<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Mapper;

use ArrayObject;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class TransferMapper implements TransferMapperInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     * @param array $allowedFields
     *
     * @return array
     */
    public function mapTransferDataByAllowedFields(AbstractTransfer $transfer, array $allowedFields): array
    {
        return $this->mapTransferDataByAllowedFieldsRecursive(
            $transfer,
            $allowedFields,
            [],
        );
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     * @param array $allowedFields
     * @param array $mappedData
     *
     * @return array
     */
    protected function mapTransferDataByAllowedFieldsRecursive(
        AbstractTransfer $transfer,
        array $allowedFields,
        array $mappedData
    ): array {
        $camelCasedData = $transfer->toArray(false, true);

        foreach ($allowedFields as $fieldName => $allowedData) {
            $fieldValue = $camelCasedData[$fieldName] ?? null;

            if ($fieldValue == null) {
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
