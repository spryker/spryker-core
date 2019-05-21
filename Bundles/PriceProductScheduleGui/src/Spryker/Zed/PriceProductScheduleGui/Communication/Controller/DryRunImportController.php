<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleImportFormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductScheduleGui\Persistence\PriceProductScheduleGuiRepositoryInterface getRepository()
 */
class DryRunImportController extends AbstractController
{
    public const URL_IMPORT_PAGE = 'price-product-schedule-gui/import';

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

        $priceProductScheduleListImportResponseTransfer = $this->handlePriceProductScheduleImportForm(
            $priceProductScheduleImportForm
        );

        $errorTable = $this->getFactory()
            ->createImportErrorTable($priceProductScheduleListImportResponseTransfer);
        $errorTableData = $this->getTableArrayFormat($errorTable);

        $successTable = $this->getFactory()
            ->createImportSuccessListTable($priceProductScheduleListImportResponseTransfer->getPriceProductScheduleList());

        return $this->viewResponse([
            'importForm' => $priceProductScheduleImportForm->createView(),
            'priceProductScheduleList' => $priceProductScheduleListImportResponseTransfer->getPriceProductScheduleList(),
            'errorTable' => $errorTableData,
            'successTable' => $successTable->render(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request)
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
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer
     */
    protected function handlePriceProductScheduleImportForm(
        FormInterface $importForm
    ): PriceProductScheduleListImportResponseTransfer {
        $priceProductScheduleName = $importForm
            ->get(PriceProductScheduleImportFormType::FIELD_PRICE_PRODUCT_SCHEDULE_NAME)
            ->getData();

        $priceProductScheduleListImportRequestTransfer = (new PriceProductScheduledListImportRequestTransfer())
            ->setPriceProductScheduleList(
                (new PriceProductScheduleListTransfer())->setName($priceProductScheduleName)
            );

        $importCsv = $importForm
            ->get(PriceProductScheduleImportFormType::FIELD_FILE_UPLOAD)
            ->getData();

        $priceProductScheduleListImportRequestTransfer = $this->getFactory()
            ->createPriceProductScheduleImporter()
            ->importPriceProductScheduleImportTransfersFromCsvFile(
                $importCsv,
                $priceProductScheduleListImportRequestTransfer
            );

        return $this->getFactory()->getPriceProductScheduleFacade()->importPriceProductSchedules($priceProductScheduleListImportRequestTransfer);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\AbstractTable $table
     *
     * @return array
     */
    protected function getTableArrayFormat(AbstractTable $table)
    {
        $tableData = $table->fetchData();
        $tableData['header'] = $table->getConfiguration()->getHeader();

        return $tableData;
    }
}
