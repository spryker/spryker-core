<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Client\Cms\Service;

use Generated\Shared\Transfer\CmsBlockTransfer;
use SprykerEngine\Client\Kernel\Service\AbstractClient;

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
     *
     * @return CmsBlockStorageInterface
     */
    private function createCmsBlockFinder()
    {
        return $this->getDependencyContainer()->createCmsBlockFinder();
    }
}
