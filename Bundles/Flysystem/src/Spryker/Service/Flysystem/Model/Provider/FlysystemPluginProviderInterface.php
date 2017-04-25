<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Provider;

use League\Flysystem\Filesystem;

interface FlysystemPluginProviderInterface
{

    /**
     * @param \League\Flysystem\Filesystem $filesystem
     *
     * @return \League\Flysystem\Filesystem
     */
    public function provide(Filesystem $filesystem);

}
