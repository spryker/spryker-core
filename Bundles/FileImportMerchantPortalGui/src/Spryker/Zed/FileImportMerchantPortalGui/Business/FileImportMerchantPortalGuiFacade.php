<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Business;

use Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileImportResponseTransfer;
use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Business\FileImportMerchantPortalGuiBusinessFactory getFactory()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiRepositoryInterface getRepository()
 */
class FileImportMerchantPortalGuiFacade extends AbstractFacade implements FileImportMerchantPortalGuiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportResponseTransfer
     */
    public function saveMerchantFileImport(
        MerchantFileImportTransfer $merchantFileImportTransfer
    ): MerchantFileImportResponseTransfer {
        return $this->getFactory()
            ->createMerchantFileImportSaver()
            ->saveMerchantFileImport($merchantFileImportTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer $merchantFileImportCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer|null
     */
    public function findMerchantFileImport(
        MerchantFileImportCriteriaTransfer $merchantFileImportCriteriaTransfer
    ): ?MerchantFileImportTransfer {
        return $this->getFactory()
            ->createMerchantFileImportReader()
            ->findMerchantFileImport($merchantFileImportCriteriaTransfer);
    }
}
