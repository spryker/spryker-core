<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Business;

use Generated\Shared\Transfer\ContentTransfer;

interface ContentFacadeInterface
{
    /**
     * Specification:
     * - Find content item by id
     * - Returns ContentTransfer if found, NULL otherwise
     *
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    public function findContentById(int $id): ?ContentTransfer;

    /**
     * Specification:
     * - Find content item by uuid
     * - Returns ContentTransfer if found, NULL otherwise.
     *
     * @api
     *
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    public function findContentByUUID(string $uuid): ?ContentTransfer;

    /**
     * Specification:
     * - Creates a new content item entity
     * - Uses incoming transfer to set entity fields
     * - Persists the entity to DB
     * - Sets ID to the returning transfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function create(ContentTransfer $contentTransfer): ContentTransfer;

    /**
     * Specification:
     * - Finds a content item record by ID in DB
     * - Throws exception if not found
     * - Uses incoming transfer to set entity fields
     * - Persists the entity to DB
     * - Sets ID to the returning transfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function update(ContentTransfer $contentTransfer): ContentTransfer;
}
