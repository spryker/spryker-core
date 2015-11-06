<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Client\Cms\Service\Storage;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockStorageInterface
{

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return array
     */
    public function getBlockByName(CmsBlockTransfer $cmsBlockTransfer);

}
