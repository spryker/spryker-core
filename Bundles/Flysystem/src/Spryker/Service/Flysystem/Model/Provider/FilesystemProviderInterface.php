<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Provider;

interface FilesystemProviderInterface
{

    /**
     * @param string $name
     *
     * @throws \Spryker\Service\Flysystem\Exception\FilesystemNotFoundException
     *
     * @return \League\Flysystem\Filesystem
     */
    public function getFilesystemByName($name);

    /**
     * @return \League\Flysystem\Filesystem[]
     */
    public function getFilesystemCollection();

}
