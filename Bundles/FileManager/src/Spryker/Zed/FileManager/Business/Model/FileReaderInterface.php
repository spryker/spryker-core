<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

interface FileReaderInterface
{
    /**
     * @param int $idFileInfo
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\FileManagerSaveRequestTransfer
     */
    public function read(int $idFileInfo);

    /**
     * @param int $idFile
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return bool|\Generated\Shared\Transfer\FileManagerSaveRequestTransfer
     */
    public function readLatestByFileId(int $idFile);
}
