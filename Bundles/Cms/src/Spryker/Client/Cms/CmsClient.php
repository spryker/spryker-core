<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cms;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Client\Kernel\AbstractClient;

class CmsClient extends AbstractClient implements CmsClientInterface
{

    /**
     * @api
     *
     * @deprecated Use CMS Block module instead
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return array
     */
    public function findBlockByName(CmsBlockTransfer $cmsBlockTransfer)
    {
        return $this->createCmsBlockFinder()->getBlockByName($cmsBlockTransfer);
    }

    /**
     * @deprecated Use CMS Block module instead
     *
     * @return \Spryker\Client\Cms\Storage\CmsBlockStorageInterface
     */
    private function createCmsBlockFinder()
    {
        return $this->getFactory()->createCmsBlockFinder();
    }

}
