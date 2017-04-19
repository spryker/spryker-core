<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Service\Storage;

interface BuilderInterface
{

    /**
     * @throws \Spryker\Zed\FileSystem\Service\Exception\FileSystemInvalidConfigurationException
     *
     * @return \Spryker\Zed\FileSystem\Service\Storage\FileSystemStorageInterface
     */
    public function build();

}
