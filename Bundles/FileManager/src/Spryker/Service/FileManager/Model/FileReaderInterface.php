<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileManager\Model;

interface FileReaderInterface
{
    /**
     * @param string $fileName
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function read(string $fileName);

    /**
     * @param string $fileName
     *
     * @return mixed
     */
    public function readStream(string $fileName);
}
