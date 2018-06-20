<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Business\Storage;

interface FileManagerStorageWriterInterface
{
    /**
     * @param array $fileIds
     *
     * @return void
     */
    public function publish(array $fileIds);

    /**
     * @param array $fileIds
     *
     * @return void
     */
    public function unpublish(array $fileIds);
}
