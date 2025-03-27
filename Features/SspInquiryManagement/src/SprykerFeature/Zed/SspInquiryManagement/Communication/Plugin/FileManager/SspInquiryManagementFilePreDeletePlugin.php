<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication\Plugin\FileManager;

use Generated\Shared\Transfer\FileCollectionTransfer;
use Spryker\Zed\FileManagerExtension\Dependency\Plugin\FilePreDeletePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementBusinessFactory getBusinessFactory()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Communication\SspInquiryManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 */
class SspInquiryManagementFilePreDeletePlugin extends AbstractPlugin implements FilePreDeletePluginInterface
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
        $this->getBusinessFactory()->createSspAssetFileDeleter()->deleteSspAssetFile($fileCollectionTransfer);

        return $fileCollectionTransfer;
    }
}
