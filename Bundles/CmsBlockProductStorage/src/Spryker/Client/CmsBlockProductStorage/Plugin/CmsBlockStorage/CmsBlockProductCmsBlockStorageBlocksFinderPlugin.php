<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockProductStorage\Plugin\CmsBlockStorage;

use Spryker\Client\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageBlocksFinderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\CmsBlockProductStorage\CmsBlockProductStorageFactory getFactory()
 */
class CmsBlockProductCmsBlockStorageBlocksFinderPlugin extends AbstractPlugin implements CmsBlockStorageBlocksFinderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $cmsBlockOptions
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getRelatedCmsBlocks(array $cmsBlockOptions): array
    {
        return $this->getFactory()->createCmsBlockProductStorageReader()->getCmsBlocksByOptions($cmsBlockOptions);
    }
}
