<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Business;

use Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlock\CmsBlockFeatureDetector;
use Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlock\CmsBlockFeatureDetectorInterface;
use Spryker\Zed\CmsBlockCategoryStorage\Business\Storage\CmsBlockCategoryStorageWriter;
use Spryker\Zed\CmsBlockCategoryStorage\Business\Storage\CmsBlockCategoryStorageWriterInterface;
use Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageDependencyProvider;
use Spryker\Zed\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageConfig getConfig()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface getQueryContainer()
 */
class CmsBlockCategoryStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsBlockCategoryStorage\Business\Storage\CmsBlockCategoryStorageWriterInterface
     */
    public function createCmsBlockCategoryStorageWriter(): CmsBlockCategoryStorageWriterInterface
    {
        return new CmsBlockCategoryStorageWriter(
            $this->getQueryContainer(),
            $this->getUtilSanitizeService(),
            $this->getConfig()->isSendingToQueue(),
            $this->createCmsBlockFeatureDetector()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService(): CmsBlockCategoryStorageToUtilSanitizeServiceInterface
    {
        return $this->getProvidedDependency(CmsBlockCategoryStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlock\CmsBlockFeatureDetectorInterface
     */
    public function createCmsBlockFeatureDetector(): CmsBlockFeatureDetectorInterface
    {
        return new CmsBlockFeatureDetector();
    }
}
