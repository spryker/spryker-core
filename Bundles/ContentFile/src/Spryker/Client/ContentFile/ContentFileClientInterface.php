<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentFile;

use Generated\Shared\Transfer\ContentFileListTypeTransfer;

interface ContentFileClientInterface
{
    /**
     * Specification:
     * - Finds content item in by contentKey from.
     * - Executes a FileListType's contentTerm into a ContentFileListType.
     *
     * @api
     *
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentFileListTypeTransfer|null
     */
    public function executeFileListTypeByKey(string $contentKey, string $localeName): ?ContentFileListTypeTransfer;
}
