<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use Generated\Shared\Transfer\PriceProductScheduleCsvValidationResultTransfer;
use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleImportFormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class DryRunImportController extends AbstractController
{
    public const URL_IMPORT_PAGE = '/price-product-schedule-gui/import';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $priceProductScheduleImportForm = $this
            ->getFactory()
            ->getPriceProductScheduleImportForm();

        $priceProductScheduleImportForm->handleRequest($request);

        if (!$priceProductScheduleImportForm->isSubmitted() || !$priceProductScheduleImportForm->isValid()) {
            return $this->redirectResponse(static::URL_IMPORT_PAGE);
        }

        $priceProductScheduleCsvValidationResultTransfer = $this->validatePriceProductScheduleImportForm($priceProductScheduleImportForm);

        if ($priceProductScheduleCsvValidationResultTransfer->getIsSuccess() === false) {
            $this->addErrorMessage($priceProductScheduleCsvValidationResultTransfer->getError());

            return $this->redirectResponse(static::URL_IMPORT_PAGE);
        }

        return $this->prepareIndexData($request, $priceProductScheduleImportForm);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $priceProductScheduleList = (new PriceProductScheduleListTransfer())
            ->setIdPriceProductScheduleList($request->query->getInt(PriceProductScheduleListTransfer::ID_PRICE_PRODUCT_SCHEDULE_LIST));

        $successTable = $this->getFactory()
            ->createImportSuccessListTable($priceProductScheduleList);

        return $this->jsonResponse(
            $successTable->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $importForm
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleCsvValidationResultTransfer
     */
    protected function validatePriceProductScheduleImportForm(
        FormInterface $importForm
    ): PriceProductScheduleCsvValidationResultTransfer {
        $uploadedFile = $importForm
            ->get(PriceProductScheduleImportFormType::FIELD_FILE_UPLOAD)
            ->getData();

        return $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->validateCsvFile($uploadedFile);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $importForm
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer
     */
    protected function handlePriceProductScheduleImportForm(
        FormInterface $importForm,
        Request $request
    ): PriceProductScheduleListImportResponseTransfer {
        $priceProductScheduleName = $importForm
            ->get(PriceProductScheduleImportFormType::FIELD_PRICE_PRODUCT_SCHEDULE_NAME)
            ->getData();

        $uploadedFile = $importForm
            ->get(PriceProductScheduleImportFormType::FIELD_FILE_UPLOAD)
            ->getData();

        $priceProductScheduledList = $this->getPriceProductScheduleListTransfer($request, $priceProductScheduleName);
        $priceProductScheduleListImportRequestTransfer = (new PriceProductScheduledListImportRequestTransfer())
            ->setPriceProductScheduleList($priceProductScheduledList);

        $priceProductScheduleListImportRequestTransfer = $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->readPriceProductScheduleImportTransfersFromCsvFile(
                $uploadedFile,
                $priceProductScheduleListImportRequestTransfer
            );

        return $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->importPriceProductSchedules($priceProductScheduleListImportRequestTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $priceProductScheduleName
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    protected function getPriceProductScheduleListTransfer(
        Request $request,
        string $priceProductScheduleName
    ): PriceProductScheduleListTransfer {
        if ($request->query->has(PriceProductScheduleListTransfer::ID_PRICE_PRODUCT_SCHEDULE_LIST)) {
            $idPriceProductScheduleList = $request->query->getInt(PriceProductScheduleListTransfer::ID_PRICE_PRODUCT_SCHEDULE_LIST);

            return (new PriceProductScheduleListTransfer())
                ->setIdPriceProductScheduleList($idPriceProductScheduleList);
        }

        $priceProductScheduledListResponse = $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->createPriceProductScheduleList(
                (new PriceProductScheduleListTransfer())->setName($priceProductScheduleName)
            );

        return $priceProductScheduledListResponse->getPriceProductScheduleList();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $priceProductScheduleImportForm
     *
     * @return array
     */
    protected function prepareIndexData(Request $request, FormInterface $priceProductScheduleImportForm): array
    {
        $priceProductScheduleListImportResponseTransfer = $this->handlePriceProductScheduleImportForm(
            $priceProductScheduleImportForm,
            $request
        );

        $errorTable = $this->getFactory()
            ->createImportErrorTable($priceProductScheduleListImportResponseTransfer);

        $errorTableData = $this->getFactory()
            ->createTableFormatter()
            ->formatAbstractTableToArray($errorTable);

        $priceProductScheduleList = $priceProductScheduleListImportResponseTransfer
            ->getPriceProductScheduleList();

        $successTable = $this->getFactory()
            ->createImportSuccessListTable($priceProductScheduleList);

        $successTable->fetchData();

        return $this->viewResponse([
            'importForm' => $priceProductScheduleImportForm->createView(),
            'priceProductScheduleList' => $priceProductScheduleList,
            'errorTable' => $errorTableData,
            'renderSuccessTable' => empty($successTable->getData()) !== true,
            'successTableView' => $successTable->render(),
        ]);
    }
}
