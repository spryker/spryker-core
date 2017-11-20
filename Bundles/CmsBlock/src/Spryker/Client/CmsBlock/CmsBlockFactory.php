<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlock;

use Spryker\Client\CmsBlock\KeyBuilder\CmsBlockKeyBuilder;
use Spryker\Client\CmsBlock\Storage\CmsBlockStorage;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\CmsBlock\CmsBlockConfig getConfig
 */
class CmsBlockFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsBlock\Storage\CmsBlockStorageInterface
     */
    public function createCmsBlockFinder()
    {
        return new CmsBlockStorage(
            $this->getStorage(),
            $this->createKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Client\CmsBlock\Dependency\Client\CmsBlockToStorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(CmsBlockDependencyProvider::KV_STORAGE);
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createKeyBuilder()
    {
        return new CmsBlockKeyBuilder();
    }
}
