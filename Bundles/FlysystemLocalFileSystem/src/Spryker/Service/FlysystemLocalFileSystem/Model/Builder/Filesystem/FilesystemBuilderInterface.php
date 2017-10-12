<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemLocalFileSystem\Model\Builder\Filesystem;

interface FilesystemBuilderInterface
{
    /**
     * @return \League\Flysystem\Filesystem
     */
    public function build();
}
