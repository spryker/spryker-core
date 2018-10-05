<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Business\Storage;

interface FileManagerStorageWriterInterface
{
    /**
     * @param int[] $fileIds
     *
     * @return bool
     */
    public function publish(array $fileIds);

    /**
     * @param int[] $fileIds
     *
     * @return bool
     */
    public function unpublish(array $fileIds);
}
