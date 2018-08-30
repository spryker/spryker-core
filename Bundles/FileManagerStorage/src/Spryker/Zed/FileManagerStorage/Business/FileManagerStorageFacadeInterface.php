<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Business;

interface FileManagerStorageFacadeInterface
{
    /**
     * Specification
     * - Publishes files with given ids
     *
     * @api
     *
     * @param int[] $fileIds
     *
     * @return void
     */
    public function publishFile(array $fileIds);

    /**
     * Specification
     * - Unpiblishes files with given ids
     *
     * @api
     *
     * @param int[] $fileIds
     *
     * @return void
     */
    public function unpublishFile(array $fileIds);
}
