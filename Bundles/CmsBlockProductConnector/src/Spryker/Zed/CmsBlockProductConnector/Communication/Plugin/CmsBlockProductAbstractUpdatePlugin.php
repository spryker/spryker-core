<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Plugin;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlock\Communication\Plugin\CmsBlockUpdatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockProductConnector\Communication\CmsBlockProductConnectorCommunicationFactory getFactory()
 */
class CmsBlockProductAbstractUpdatePlugin extends AbstractPlugin implements CmsBlockUpdatePluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function handleUpdate(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->getFacade()
            ->updateCmsBlockProductAbstractRelations($cmsBlockTransfer);
    }
}
