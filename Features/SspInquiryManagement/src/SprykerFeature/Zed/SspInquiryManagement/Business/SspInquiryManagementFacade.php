<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementBusinessFactory getFactory()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementEntityManagerInterface getEntityManager()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface getRepository()
 */
class SspInquiryManagementFacade extends AbstractFacade implements SspInquiryManagementFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function createSspInquiryCollection(SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer): SspInquiryCollectionResponseTransfer
    {
        return $this->getFactory()->createSspInquiryWriter()->createSspInquiryCollection($sspInquiryCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer
    {
        return $this->getFactory()->createSspInquiryReader()->getSspInquiryCollection($sspInquiryCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function cancelSspInquiryCollection(SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer): SspInquiryCollectionResponseTransfer
    {
        return $this->getFactory()->createSspInquiryStateWriter()->cancelSspInquiry($sspInquiryCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importSspInquiry(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer): DataImporterReportTransfer
    {
        return $this->getFactory()->getSspInquiryDataImporter()->import($dataImporterConfigurationTransfer);
    }
}
