<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Client\Cms\Storage;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockStorageInterface
{

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return array
     */
    public function getBlockByName(CmsBlockTransfer $cmsBlockTransfer);

}
