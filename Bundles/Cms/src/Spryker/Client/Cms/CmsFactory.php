<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cms;

use Spryker\Client\Cms\KeyBuilder\CmsBlockKeyBuilder;
use Spryker\Client\Cms\Storage\CmsBlockStorage;
use Spryker\Client\Cms\Zed\CmsStub;
use Spryker\Client\Kernel\AbstractFactory;

class CmsFactory extends AbstractFactory
{
    /**
     * @deprecated Use CMS Block module instead
     *
     * @return \Spryker\Client\Cms\Storage\CmsBlockStorageInterface
     */
    public function createCmsBlockFinder()
    {
        return new CmsBlockStorage(
            $this->getStorage(),
            $this->createKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::KV_STORAGE);
    }

    /**
     * @deprecated Use CMS Block module instead
     *
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createKeyBuilder()
    {
        return new CmsBlockKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Cms\Zed\CmsStub
     */
    public function createCmsStub()
    {
        return new CmsStub($this->getProvidedDependency(CmsDependencyProvider::CLIENT_ZED_REQUEST));
    }
}
