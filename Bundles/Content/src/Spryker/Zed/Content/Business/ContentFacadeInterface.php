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
     *
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function findContentById(int $id): ContentTransfer;

    /**
     * Specification:
     * - Find content item by uuid
     *
     * @api
     *
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function findContentByUUID(string $uuid): ContentTransfer;

    /**
     * Specification:
     * - Create content item by uuid
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
     * - Update content item by uuid
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function update(ContentTransfer $contentTransfer): ContentTransfer;

    /**
     * Specification:
     * - Delete content item by uuid
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return void
     */
    public function delete(ContentTransfer $contentTransfer): void;
}
