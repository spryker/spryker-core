<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\CmsBlockStorage;

use Spryker\Zed\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageRelatedBlocksFinderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Communication\CmsBlockCategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageConfig getConfig()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlockCategoryStorageFacadeInterface getFacade()
 */
class CmsBlockCategoryCmsBlockStorageRelatedBlocksFinderPlugin extends AbstractPlugin implements CmsBlockStorageRelatedBlocksFinderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function findRelatedCmsBlocks(array $options): array
    {
        return $this->getFactory()->createCmsBlockCategoryStorageReader()->findCmsBlocksByOptions($options);
    }
}
