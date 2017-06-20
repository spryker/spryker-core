<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Business;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorBusinessFactory getFactory()
 */
class CmsBlockProductConnectorFacade extends AbstractFacade implements CmsBlockProductConnectorFacadeInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function updateCmsBlockProductAbstractRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->getFactory()
            ->createCmsBlockProductAbstractWriter()
            ->updateCmsBlockProductAbstractRelations($cmsBlockTransfer);
    }

}
