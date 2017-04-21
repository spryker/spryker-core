<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model;

interface FileSystemStorageInterface
{

    /**
     * @return \League\Flysystem\Filesystem
     */
    public function getFileSystem();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getType();

}
