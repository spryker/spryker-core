<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Reader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface RelationCsvReaderInterface
{
    /**
     * @var string
     */
    public const KEY_ENTITY_IDENTIFIERS_TO_BE_ATTACHED = 'toBeAttached';

    /**
     * @var string
     */
    public const KEY_ENTITY_IDENTIFIERS_TO_BE_UNATTACHED = 'toBeUnattached';

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     *
     * @return array<string, array<string>>
     */
    public function readRelations(UploadedFile $uploadedFile): array;
}
