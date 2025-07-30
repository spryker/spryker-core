<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Communication\Plugin\MerchantFile;

use Generated\Shared\Transfer\MerchantFileTransfer;
use Spryker\Shared\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantFileExtension\Dependency\Plugin\MerchantFilePostSavePluginInterface;

/**
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Business\FileImportMerchantPortalGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Communication\FileImportMerchantPortalGuiCommunicationFactory getFactory()
 */
class MerchantFileImportMerchantFilePostSavePlugin extends AbstractPlugin implements MerchantFilePostSavePluginInterface
{
    /**
     * @api
     *
     * @inheritDoc
     */
    public function execute(MerchantFileTransfer $merchantFileTransfer): MerchantFileTransfer
    {
        if ($merchantFileTransfer->getType() !== FileImportMerchantPortalGuiConfig::FILE_TYPE_DATA_IMPORT) {
            return $merchantFileTransfer;
        }

        $merchantFileImportTransfer = $merchantFileTransfer->getMerchantFileImportOrFail();

        $merchantFileImportTransfer->setFkMerchantFile(
            $merchantFileTransfer->getIdMerchantFileOrFail(),
        );

        $this->getFacade()->saveMerchantFileImport($merchantFileImportTransfer);

        return $merchantFileTransfer;
    }
}
