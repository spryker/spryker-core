<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantCommissionGui\Communication\Form\MerchantCommissionImportForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \Spryker\Zed\MerchantCommissionGui\Communication\MerchantCommissionGuiCommunicationFactory getFactory()
 */
class ImportController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\Communication\Controller\ListController::indexAction()
     *
     * @var string
     */
    protected const URL_MERCHANT_RELATION_REQUEST_LIST = '/merchant-commission-gui/list';

    /**
     * @var string
     */
    protected const STREAM_PHP_OUTPUT = 'php://output';

    /**
     * @var string
     */
    protected const MERCHANT_COMMISSION_CSV_TEMPLATE_FILE_NAME = 'commissions_template.csv';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_DISPOSITION = 'Content-Disposition';

    /**
     * @var string
     */
    protected const ACCESS_MODE_TYPE_READ_WRITE = 'w+';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request): RedirectResponse|array
    {
        $merchantCommissionImportForm = $this->getFactory()->getMerchantCommissionImportForm();
        $merchantCommissionImportForm->handleRequest($request);
        if ($merchantCommissionImportForm->isSubmitted() && $merchantCommissionImportForm->isValid()) {
            return $this->handleFormSubmission($merchantCommissionImportForm);
        }

        return $this->viewResponse([
            'form' => $merchantCommissionImportForm->createView(),
            'errorTable' => null,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadTemplateAction(): StreamedResponse
    {
        $streamedResponse = new StreamedResponse();

        $columnsList = $this->getFactory()->getConfig()->getCsvFileRequiredColumnsList();
        $streamedResponse->setCallback(function () use ($columnsList): void {
            /** @var resource $outputResource */
            $outputResource = fopen(static::STREAM_PHP_OUTPUT, static::ACCESS_MODE_TYPE_READ_WRITE);
            fputcsv($outputResource, $columnsList);
            fclose($outputResource);
        });

        $streamedResponse->headers->set(static::HEADER_CONTENT_TYPE, 'text/csv; charset=utf-8');
        $streamedResponse->headers->set(
            static::HEADER_CONTENT_DISPOSITION,
            sprintf('attachment; filename="%s"', static::MERCHANT_COMMISSION_CSV_TEMPLATE_FILE_NAME),
        );

        return $streamedResponse->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantCommissionImportForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    protected function handleFormSubmission(FormInterface $merchantCommissionImportForm): RedirectResponse|array
    {
        $uploadedFile = $merchantCommissionImportForm
            ->get(MerchantCommissionImportForm::FIELD_FILE_UPLOAD)
            ->getData();

        $errorTransfers = $this->getFactory()
            ->createMerchantCommissionCsvValidator()
            ->validateMerchantCommissionCsvFile($uploadedFile);
        if ($errorTransfers->count() !== 0) {
            foreach ($errorTransfers as $errorTransfer) {
                $this->addErrorMessage($errorTransfer->getMessageOrFail());
            }

            return $this->viewResponse([
                'form' => $merchantCommissionImportForm->createView(),
                'errorTable' => null,
            ]);
        }

        $merchantCommissionTransfers = $this->getFactory()
            ->createMerchantCommissionCsvReader()
            ->readMerchantCommissionTransfersFromCsvFile($uploadedFile);

        if ($merchantCommissionTransfers === []) {
            return $this->successfulImportRedirect();
        }

        $merchantCommissionCollectionRequestTransfer = (new MerchantCommissionCollectionRequestTransfer())
            ->setMerchantCommissions(new ArrayObject($merchantCommissionTransfers))
            ->setIsTransactional($this->getFactory()->getConfig()->isTransactionalDataImport());

        $merchantCommissionCollectionResponseTransfer = $this->getFactory()
            ->getMerchantCommissionFacade()
            ->importMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);

        if ($merchantCommissionCollectionResponseTransfer->getErrors()->count() === 0) {
            return $this->successfulImportRedirect();
        }

        $errorTable = $this->getFactory()
            ->createMerchantCommissionImportErrorTable($merchantCommissionCollectionResponseTransfer);

        return $this->viewResponse([
            'form' => $merchantCommissionImportForm->createView(),
            'errorTable' => $this->prepareTableData($errorTable),
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\AbstractTable $table
     *
     * @return array<string, mixed>
     */
    protected function prepareTableData(AbstractTable $table): array
    {
        $tableData = $table->fetchData();
        $tableData['header'] = $table->getConfiguration()->getHeader();

        return $tableData;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function successfulImportRedirect(): RedirectResponse
    {
        $this->addSuccessMessage('Merchant commissions imported successfully.');

        return $this->redirectResponse(static::URL_MERCHANT_RELATION_REQUEST_LIST);
    }
}
