<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\CmsBlockStorage;

use Spryker\Zed\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageBlocksFinderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Communication\CmsBlockCategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageConfig getConfig()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlockCategoryStorageFacadeInterface getFacade()
 */
class CmsBlockCategoryCmsBlockStorageBlocksFinderPlugin extends AbstractPlugin implements CmsBlockStorageBlocksFinderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $cmsBlockOptions
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getRelatedCmsBlocks(array $cmsBlockOptions): array
    {
        return $this->getFactory()->createCmsBlockCategoryStorageReader()->getCmsBlocksByOptions($cmsBlockOptions);
    }
}
