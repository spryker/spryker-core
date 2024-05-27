<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication\Controller;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportConnectionConfigurationTransfer;
use Generated\Shared\Transfer\DataExportFormatConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \Spryker\Zed\MerchantCommissionGui\Communication\MerchantCommissionGuiCommunicationFactory getFactory()
 */
class ExportController extends AbstractController
{
    /**
     * @uses \Spryker\Service\DataExport\Plugin\DataExport\OutputStreamDataExportConnectionPlugin::CONNECTION_TYPE_OUTPUT_STREAM
     *
     * @var string
     */
    protected const CONNECTION_TYPE_OUTPUT_STREAM = 'output-stream';

    /**
     * @uses \Spryker\Service\DataExport\Formatter\DataExportFormatter::DEFAULT_FORMAT_TYPE
     *
     * @var string
     */
    protected const FORMAT_CSV = 'csv';

    /**
     * @var string
     */
    protected const STREAM_PHP_OUTPUT = 'php://output';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_DISPOSITION = 'Content-Disposition';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\Communication\Controller\ListController::indexAction()
     *
     * @var string
     */
    protected const URL_MERCHANT_RELATION_REQUEST_LIST = '/merchant-commission-gui/list';

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(): Response
    {
        $dataExportConfigurationTransfer = $this->createDataExportConfigurationTransfer();

        $dataExportReportTransfer = $this->getFactory()
            ->getMerchantCommissionDataExportFacade()
            ->exportMerchantCommission($dataExportConfigurationTransfer);

        if (!$dataExportReportTransfer->getIsSuccessfulOrFail()) {
            $this->addErrorMessages($dataExportReportTransfer);

            return $this->redirectResponse(static::URL_MERCHANT_RELATION_REQUEST_LIST);
        }

        $streamedResponse = new StreamedResponse();
        $streamedResponse->setContent(null);
        $streamedResponse->headers->set(static::HEADER_CONTENT_TYPE, 'text/csv; charset=utf-8');
        $streamedResponse->headers->set(
            static::HEADER_CONTENT_DISPOSITION,
            sprintf('attachment; filename="%s"', $this->getFactory()->getConfig()->getMerchantCommissionsExportFileName()),
        );

        return $streamedResponse->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    protected function createDataExportConfigurationTransfer(): DataExportConfigurationTransfer
    {
        $dataExportFormatConfigurationTransfer = (new DataExportFormatConfigurationTransfer())
            ->setType(static::FORMAT_CSV);

        $dataExportConnectionConfigurationTransfer = (new DataExportConnectionConfigurationTransfer())
            ->setType(static::CONNECTION_TYPE_OUTPUT_STREAM);

        return (new DataExportConfigurationTransfer())
            ->setFormat($dataExportFormatConfigurationTransfer)
            ->setConnection($dataExportConnectionConfigurationTransfer)
            ->setDestination(static::STREAM_PHP_OUTPUT)
            ->setFields($this->getFactory()->getConfig()->getCsvFileRequiredColumnsList());
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportReportTransfer $dataExportReportTransfer
     *
     * @return void
     */
    protected function addErrorMessages(DataExportReportTransfer $dataExportReportTransfer): void
    {
        foreach ($dataExportReportTransfer->getDataExportResults() as $dataExportResultTransfer) {
            foreach ($dataExportResultTransfer->getMessages() as $messageTransfer) {
                $this->addErrorMessage($messageTransfer->getValueOrFail());
            }
        }
    }
}
