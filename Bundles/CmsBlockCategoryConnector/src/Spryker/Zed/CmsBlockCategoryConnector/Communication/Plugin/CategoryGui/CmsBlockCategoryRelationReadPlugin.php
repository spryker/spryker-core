<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin\CategoryGui;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryRelationReadPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Communication\CmsBlockCategoryConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig getConfig()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface getQueryContainer()
 */
class CmsBlockCategoryRelationReadPlugin extends AbstractPlugin implements CategoryRelationReadPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getRelationName(): string
    {
        return 'CMS Blocks';
    }

    /**
     * {@inheritDoc}
     * - Returns cms block names related to the category, indexed by IdCmsBlock.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function getRelations(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer): array
    {
        $cmsBlocks = [];
        $cmsBlockTransfers = $this
            ->getFacade()
            ->getCmsBlockCollection($categoryTransfer->getIdCategory(), $categoryTransfer->getFkCategoryTemplate());

        foreach ($cmsBlockTransfers as $cmsBlockTransfer) {
            $cmsBlocks[$cmsBlockTransfer->getIdCmsBlock()] = $cmsBlockTransfer->getName();
        }

        return $cmsBlocks;
    }
}
