<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage\ContentStorage;

use Generated\Shared\Transfer\ContentQueryTransfer;
use Generated\Shared\Transfer\ExecutedContentStorageTransfer;

interface ContentStorageReaderInterface
{
    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ExecutedContentStorageTransfer|null
     */
    public function findContentById(int $idContent, string $localeName): ?ExecutedContentStorageTransfer;

    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentQueryTransfer|null
     */
    public function findContentQueryById(int $idContent, string $localeName): ?ContentQueryTransfer;
}
