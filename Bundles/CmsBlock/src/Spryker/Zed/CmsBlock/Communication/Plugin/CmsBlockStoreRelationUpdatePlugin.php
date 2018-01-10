<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Communication\Plugin;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlock\Communication\CmsBlockCommunicationFactory getFactory()
 */
class CmsBlockStoreRelationUpdatePlugin extends AbstractPlugin implements CmsBlockUpdatePluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function handleUpdate(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->assertStoreRelationEntityId($cmsBlockTransfer);

        $this->getFacade()->updateCmsBlockStoreRelation($cmsBlockTransfer->getStoreRelation());
    }

    /**
     * Sets newly created CMS Block's store relation entity ID
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    protected function assertStoreRelationEntityId(CmsBlockTransfer $cmsBlockTransfer)
    {
        if ($cmsBlockTransfer->getStoreRelation()->getIdEntity() === null) {
            $cmsBlockTransfer->getStoreRelation()->setIdEntity($cmsBlockTransfer->getIdCmsBlock());
        }
    }
}
