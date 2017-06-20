<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Business;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockProductConnectorFacadeInterface
{

    /**
     * Specification
     * - delete all relations of cms block to product abstracts
     * - create relations by transfer object
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function updateCmsBlockProductAbstractRelations(CmsBlockTransfer $cmsBlockTransfer);

}
