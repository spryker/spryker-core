<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemAws3v3FileSystem\Model\Builder\Filesystem;

use League\Flysystem\Filesystem;

interface FilesystemBuilderInterface
{
    /**
     * @return \League\Flysystem\Filesystem
     */
    public function build(): Filesystem;
}
