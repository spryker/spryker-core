<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemAws3v3FileSystem\Model\Builder\Adapter;

use League\Flysystem\FilesystemAdapter;

interface AdapterBuilderInterface
{
    /**
     * @return \League\Flysystem\FilesystemAdapter
     */
    public function build(): FilesystemAdapter;
}
