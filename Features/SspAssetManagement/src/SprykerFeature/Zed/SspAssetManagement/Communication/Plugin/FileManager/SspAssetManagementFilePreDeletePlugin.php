<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Communication\Plugin\FileManager;

use Generated\Shared\Transfer\FileCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Spryker\Zed\FileManagerExtension\Dependency\Plugin\FilePreDeletePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspAssetManagement\Communication\SspAssetManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig getConfig()
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
        $fileIds = [];
        foreach ($fileCollectionTransfer->getFiles() as $fileTransfer) {
            $fileIds[] = $fileTransfer->getIdFileOrFail();
        }

        $sspAssetCollection = $this->getFacade()->getSspAssetCollection(
            (new SspAssetCriteriaTransfer())->setSspAssetConditions(
                (new SspAssetConditionsTransfer())->setFileIds($fileIds),
            ),
        );

        foreach ($sspAssetCollection->getSspAssets() as $sspAssetTransfer) {
            $sspAssetTransfer->setImage(null);
        }

        $this->getFacade()->updateSspAssetCollection(
            (new SspAssetCollectionRequestTransfer())->setSspAssets($sspAssetCollection->getSspAssets()),
        );

        return $fileCollectionTransfer;
    }
}
