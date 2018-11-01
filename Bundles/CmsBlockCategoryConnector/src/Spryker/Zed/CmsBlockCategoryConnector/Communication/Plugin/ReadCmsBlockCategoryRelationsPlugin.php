<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Category\Dependency\Plugin\CategoryRelationReadPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Communication\CmsBlockCategoryConnectorCommunicationFactory getFactory()
 */
class ReadCmsBlockCategoryRelationsPlugin extends AbstractPlugin implements CategoryRelationReadPluginInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getRelationName()
    {
        return 'CMS Blocks';
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function getRelations(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer)
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
