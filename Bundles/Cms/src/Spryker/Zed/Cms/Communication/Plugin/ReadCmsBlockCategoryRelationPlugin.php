<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Plugin;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Category\Dependency\Plugin\CategoryReadRelationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Cms\Business\CmsFacade getFacade()
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 */
class ReadCmsBlockCategoryRelationPlugin extends AbstractPlugin implements CategoryReadRelationPluginInterface
{

    /**
     * @return string
     */
    public function getRelationName()
    {
        return 'CMS Blocks';
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function getRelations(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer)
    {
        /** @var \Generated\Shared\Transfer\NodeTransfer[] $categoryNodeTransfers */
        $categoryNodeTransfers = array_merge(
            [$categoryTransfer->getCategoryNode()],
            $categoryTransfer->getExtraParents()->getArrayCopy()
        );

        $cmsBlockNames = [];

        foreach ($categoryNodeTransfers as $categoryNodeTransfer) {
            $cmsBlockTransfers = $this
                ->getFacade()
                ->getCmsBlocksByIdCategoryNode($categoryNodeTransfer->getIdCategoryNode());

            foreach ($cmsBlockTransfers as $cmsBlockTransfer) {
                $cmsBlockNames[] = $cmsBlockTransfer->getName();
            }
        }

        return array_unique($cmsBlockNames);
    }

}
