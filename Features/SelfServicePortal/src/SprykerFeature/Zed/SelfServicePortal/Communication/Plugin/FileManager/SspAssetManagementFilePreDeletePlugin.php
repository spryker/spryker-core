<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\FileManager;

use Generated\Shared\Transfer\FileCollectionTransfer;
use Spryker\Zed\FileManagerExtension\Dependency\Plugin\FilePreDeletePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory getBusinessFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 */
class SspAssetManagementFilePreDeletePlugin extends AbstractPlugin implements FilePreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Deletes ssp incuiries file relations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileCollectionTransfer $fileCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\FileCollectionTransfer
     */
    public function preDelete(FileCollectionTransfer $fileCollectionTransfer): FileCollectionTransfer
    {
        return $this->getBusinessFactory()
            ->createSspAssetManagementFileDeleter()
            ->deleteSspAssetRelationsByFileCollection($fileCollectionTransfer);
    }
}
