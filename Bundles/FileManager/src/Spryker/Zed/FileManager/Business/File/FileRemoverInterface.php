<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\File;

interface FileRemoverInterface
{
    /**
     * @param int $idFileInfo
     *
     * @return bool
     */
    public function deleteFileInfo(int $idFileInfo);

    /**
     * @param int $idFile
     *
     * @return bool
     */
    public function delete(int $idFile);
}
