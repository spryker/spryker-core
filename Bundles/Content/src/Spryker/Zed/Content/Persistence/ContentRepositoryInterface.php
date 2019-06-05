<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Persistence;

use Generated\Shared\Transfer\ContentTransfer;

interface ContentRepositoryInterface
{
    /**
     * Specification:
     * - Finds a content by content ID.
     *
     * @param int $idContent
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    public function findContentById(int $idContent): ?ContentTransfer;

    /**
     * Specification:
     * - Finds a content by content key.
     *
     * @param string $contentKey
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    public function findContentByKey(string $contentKey): ?ContentTransfer;

    /**
     * Specification:
     * - Checks whether such content key already exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool;
}
