<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Plugin\CmsBlockStorage;

use Generated\Shared\Transfer\CmsBlockRequestTransfer;
use Spryker\Client\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageReaderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class CmsBlockCompanyBusinessUnitCmsBlockStorageReaderPlugin extends AbstractPlugin implements CmsBlockStorageReaderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves company unit dedicated CMS blocks by provided company unit and company unit block name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockRequestTransfer $cmsBlockRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\CmsBlockTransfer>
     */
    public function getCmsBlocks(CmsBlockRequestTransfer $cmsBlockRequestTransfer): array
    {
        return $this->getFactory()->createCmsBlockCompanyBusinessUnitStorageReader()->getCmsBlocks($cmsBlockRequestTransfer);
    }
}
