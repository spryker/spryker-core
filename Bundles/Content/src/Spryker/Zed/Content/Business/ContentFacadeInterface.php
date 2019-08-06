<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Business;

use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;

interface ContentFacadeInterface
{
    /**
     * Specification:
     * - Finds content by id.
     * - Returns ContentTransfer if found, otherwise it returns NULL.
     *
     * @api
     *
     * @param int $idContent
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    public function findContentById(int $idContent): ?ContentTransfer;

    /**
     * Specification:
     * - Finds content by content key.
     * - Returns ContentTransfer if found, otherwise it returns NULL.
     *
     * @api
     *
     * @param string $contentKey
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    public function findContentByKey(string $contentKey): ?ContentTransfer;

    /**
     * Specification:
     * - Creates a new content entity.
     * - Uses incoming transfer to set entity fields.
     * - Persists the entity to DB.
     * - Sets ID to the returning transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @throws \Spryker\Zed\Content\Business\Exception\ContentKeyNotCreatedException
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function create(ContentTransfer $contentTransfer): ContentTransfer;

    /**
     * Specification:
     * - Finds a content record by ID in DB.
     * - Throws exception if not found.
     * - Uses incoming transfer to set entity fields.
     * - Persists the entity to DB.
     * - Sets ID to the returning transfer.
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

    /**
     * Specification:
     * - Validates content transfer.
     * - Returns ContentValidationResponseTransfer with status and messages in case of fail.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContent(ContentTransfer $contentTransfer): ContentValidationResponseTransfer;
}
