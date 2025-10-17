<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Reader;

use Spryker\Service\UtilCsv\UtilCsvServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RelationCsvReader implements RelationCsvReaderInterface
{
    public function __construct(
        protected UtilCsvServiceInterface $utilCsvService
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     *
     * @return array<string, array<string>>
     */
    public function readRelations(UploadedFile $uploadedFile): array
    {
        $csvData = $this->utilCsvService->readUploadedFile($uploadedFile);

        $entityIdentifiersToBeAttached = [];
        $entityIdentifiersToBeUnattached = [];

        foreach ($csvData as $index => $row) {
            if (!$index) {
                continue;
            }

            if (isset($row[0]) && trim($row[0])) {
                $entityIdentifiersToBeAttached[] = trim($row[0]);
            }

            if (isset($row[1]) && trim($row[1])) {
                $entityIdentifiersToBeUnattached[] = trim($row[1]);
            }
        }

        return [
            static::KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED => $entityIdentifiersToBeAttached,
            static::KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED => $entityIdentifiersToBeUnattached,
        ];
    }
}
