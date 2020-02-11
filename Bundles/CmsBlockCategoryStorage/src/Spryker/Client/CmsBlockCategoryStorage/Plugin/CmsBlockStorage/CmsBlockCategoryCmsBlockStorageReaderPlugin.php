<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockCategoryStorage\Plugin\CmsBlockStorage;

use Generated\Shared\Transfer\CmsBlockRequestTransfer;
use Spryker\Client\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageReaderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\CmsBlockCategoryStorage\CmsBlockCategoryStorageFactory getFactory()
 */
class CmsBlockCategoryCmsBlockStorageReaderPlugin extends AbstractPlugin implements CmsBlockStorageReaderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockRequestTransfer $cmsBlockRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocks(CmsBlockRequestTransfer $cmsBlockRequestTransfer): array
    {
        return $this->getFactory()->createCmsBlockCategoryStorageReader()->getCmsBlocksByOptions($cmsBlockRequestTransfer);
    }
}
