<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleImportFormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class ImportController extends AbstractController
{
    public const MESSAGE_PRICE_PRODUCT_SCHEDULE_IMPORT_SUCCESS = 'Price product schedule list has been successfully imported.';

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
            return $this->viewResponse([
                'importForm' => $priceProductScheduleImportForm->createView(),
            ]);
        }

        $priceProductScheduleListImportResponseTransfer = $this->handlePriceProductScheduleImportForm(
            $priceProductScheduleImportForm
        );

        if ($priceProductScheduleListImportResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::MESSAGE_PRICE_PRODUCT_SCHEDULE_IMPORT_SUCCESS);
        }

        return $this->redirectResponse($request->getUri());
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
            ->createPriceProductScheduleCsvReader()
            ->readPriceProductScheduleImportTransfersFromCsvFile(
                $importCsv,
                $priceProductScheduleListImportRequestTransfer
            );

        $priceProductScheduledListResponse = $this->getFactory()->getPriceProductScheduleFacade()->createPriceProductScheduleList(
            $priceProductScheduleListImportRequestTransfer->getPriceProductScheduleList()
        );

        $priceProductScheduledList = $priceProductScheduledListResponse->getPriceProductScheduleList();
        $priceProductScheduleListImportRequestTransfer->setPriceProductScheduleList($priceProductScheduledList);

        return $this->getFactory()->getPriceProductScheduleFacade()->importPriceProductSchedules(
            $priceProductScheduleListImportRequestTransfer
        );
    }
}
