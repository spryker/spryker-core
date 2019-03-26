<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage;

use Generated\Shared\Transfer\ExecutedContentStorageTransfer;
use Generated\Shared\Transfer\UnexecutedContentStorageTransfer;

interface ContentStorageClientInterface
{
    /**
     * Specification:
     * - Finds content item in the key-value storage.
     * - Resolves content type and executes data.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ExecutedContentStorageTransfer|null
     */
    public function findContentById(int $idContent, string $localeName): ?ExecutedContentStorageTransfer;

    /**
     * Specification:
     * - Retrieves content by ID through a storage client dependency
     * - Returns the pre-executed ("raw") representation.
     *
     * @api
     *
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\UnexecutedContentStorageTransfer|null
     */
    public function findUnexecutedContentById(int $idContent, string $localeName): ?UnexecutedContentStorageTransfer;
}
