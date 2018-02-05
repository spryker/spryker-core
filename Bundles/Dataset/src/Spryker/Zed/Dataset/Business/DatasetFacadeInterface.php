<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface DatasetFacadeInterface
{
    /**
     * @api
     *
     * @param int $idDataset
     *
     * @return bool
     */
    public function delete($idDataset);

    /**
     * @api
     *
     * @param int $idDataset
     *
     * @return bool
     */
    public function activateById($idDataset);

    /**
     * @api
     *
     * @param int $idDataset
     *
     * @return bool
     */
    public function deactivateById($idDataset);

    /**
     * @api
     *
     * @param null|\Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile|null $file
     *
     * @return bool
     */
    public function save(SpyDatasetEntityTransfer $saveRequestTransfer, UploadedFile $file = null);
}
