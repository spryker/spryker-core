<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

interface FileRemoverInterface
{
    /**
     * @param int $fileInfoId
     *
     * @return bool
     */
    public function deleteFileInfo(int $fileInfoId);

    /**
     * @param int $fileId
     *
     * @return bool
     */
    public function delete(int $fileId);
}
