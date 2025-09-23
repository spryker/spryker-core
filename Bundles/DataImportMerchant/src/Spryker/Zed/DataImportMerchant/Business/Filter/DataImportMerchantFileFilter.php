<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;

class DataImportMerchantFileFilter implements DataImportMerchantFileFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\DataImportMerchantFileTransfer>>
     */
    public function filterDataImportMerchantFilesByValidity(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): array {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $dataImportMerchantFileCollectionResponseTransfer->getErrors();
        $erroredEntityIdentifiers = $this->extractEntityIdentifiersFromErrorTransfers($errorTransfers);

        $validDataImportMerchantFileTransfers = new ArrayObject();
        $invalidDataImportMerchantFileTransfers = new ArrayObject();
        $index = 0;

        foreach ($dataImportMerchantFileCollectionResponseTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            $entityIdentifier = $dataImportMerchantFileTransfer->getUuid() ?? (string)$index;

            if (isset($erroredEntityIdentifiers[$entityIdentifier])) {
                $invalidDataImportMerchantFileTransfers->offsetSet($index, $dataImportMerchantFileTransfer);
                $index++;

                continue;
            }

            $validDataImportMerchantFileTransfers->offsetSet($index, $dataImportMerchantFileTransfer);
            $index++;
        }

        return [$validDataImportMerchantFileTransfers, $invalidDataImportMerchantFileTransfers];
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\DataImportMerchantFileTransfer> $validDataImportMerchantFileTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\DataImportMerchantFileTransfer> $invalidDataImportMerchantFileTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\DataImportMerchantFileTransfer>
     */
    public function mergeDataImportMerchantFiles(
        ArrayObject $validDataImportMerchantFileTransfers,
        ArrayObject $invalidDataImportMerchantFileTransfers
    ): ArrayObject {
        $index = $validDataImportMerchantFileTransfers->count();

        foreach ($invalidDataImportMerchantFileTransfers as $invalidDataImportMerchantFileTransfer) {
            $validDataImportMerchantFileTransfers->offsetSet($index, $invalidDataImportMerchantFileTransfer);
            $index++;
        }

        return $validDataImportMerchantFileTransfers;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return array<string, bool>
     */
    protected function extractEntityIdentifiersFromErrorTransfers(ArrayObject $errorTransfers): array
    {
        $erroredEntityIdentifiers = [];

        foreach ($errorTransfers as $errorTransfer) {
            $entityIdentifier = $errorTransfer->getEntityIdentifier();
            if ($entityIdentifier !== null) {
                $erroredEntityIdentifiers[$entityIdentifier] = true;
            }
        }

        return $erroredEntityIdentifiers;
    }
}
