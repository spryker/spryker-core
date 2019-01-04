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
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    public function findContentById(int $id): ?ContentTransfer;

    /**
     * Specification:
     * - Finds a content by content UUID.
     *
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    public function findContentByUUID(string $uuid): ?ContentTransfer;
}
