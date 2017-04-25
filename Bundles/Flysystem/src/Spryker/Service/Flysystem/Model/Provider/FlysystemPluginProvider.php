<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Provider;

use League\Flysystem\Filesystem;

class FlysystemPluginProvider implements FlysystemPluginProviderInterface
{

    /**
     * @var \League\Flysystem\PluginInterface[]
     */
    protected $filesystemPluginCollection;

    /**
     * @param \League\Flysystem\PluginInterface[] $filesystemPluginCollection
     */
    public function __construct(array $filesystemPluginCollection)
    {
        $this->filesystemPluginCollection = $filesystemPluginCollection;
    }

    /**
     * @param \League\Flysystem\Filesystem $filesystem
     *
     * @return \League\Flysystem\Filesystem
     */
    public function provide(Filesystem $filesystem)
    {
        foreach ($this->filesystemPluginCollection as $plugin) {
            $filesystem->addPlugin($plugin);
        }

        return $filesystem;
    }

}
