<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Client\Cms;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Client\Kernel\AbstractClient;

class CmsClient extends AbstractClient implements CmsClientInterface
{

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return array
     */
    public function findBlockByName(CmsBlockTransfer $cmsBlockTransfer)
    {
        return $this->createCmsBlockFinder()->getBlockByName($cmsBlockTransfer);
    }

    /**
     * @return CmsBlockStorageInterface
     */
    private function createCmsBlockFinder()
    {
        return $this->getFactory()->createCmsBlockFinder();
    }

}
