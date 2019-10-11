<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockProductStorage\Plugin\CmsBlockStorage;

use Spryker\Client\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageReaderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\CmsBlockProductStorage\CmsBlockProductStorageFactory getFactory()
 */
class CmsBlockProductCmsBlockStorageReaderPlugin extends AbstractPlugin implements CmsBlockStorageReaderPluginInterface
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
    public function getCmsBlocks(array $cmsBlockOptions): array
    {
        return $this->getFactory()->createCmsBlockProductStorageReader()->getCmsBlocksByOptions($cmsBlockOptions);
    }
}
