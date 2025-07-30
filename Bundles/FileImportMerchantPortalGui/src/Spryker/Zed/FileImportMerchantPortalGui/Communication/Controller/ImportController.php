<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantFileResultTransfer;
use Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface;
use Spryker\Zed\FileImportMerchantPortalGui\Communication\Form\MerchantFileImportForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Communication\FileImportMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Business\FileImportMerchantPortalGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiRepositoryInterface getRepository()
 */
class ImportController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\FileImportMerchantPortalGui\Communication\Controller\HistoryController::ID_MERCHANT_FILE_IMPORT_TABLE
     *
     * @var string
     */
    protected const ID_MERCHANT_FILE_IMPORT_TABLE = 'table-file-import-history';

    /**
     * @var string
     */
    protected const TEMPLATE_FORM_START_IMPORT = 'FileImportMerchantPortalGui/Partials/start-import.twig';

    /**
     * @var string
     */
    protected const MESSAGE_SUCCESS = 'File import has been started';

    /**
     * @var string
     */
    protected const MESSAGE_INVALID_FORM_DATA = 'Invalid form data';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uploadFileAction(Request $request): Response
    {
        $merchantFileImportFormDataProvider = $this->getFactory()->createMerchantFileImportFormDataProvider();
        $merchantFileImportForm = $this->getFactory()->createMerchantFileImportForm(
            $merchantFileImportFormDataProvider->getData(),
            $merchantFileImportFormDataProvider->getOptions(),
        );

        $merchantFileImportForm->handleRequest($request);

        $formViewResponse = $this->renderView(
            static::TEMPLATE_FORM_START_IMPORT,
            $this->getFormViewData($merchantFileImportForm),
        );

        $responseData = [
            'form' => $formViewResponse->getContent(),
        ];

        if (!$merchantFileImportForm->isSubmitted()) {
            return $this->jsonResponse($responseData);
        }

        $zedUiFormResponseBuilder = $this->getFactory()->getZedUiFactory()->createZedUiFormResponseBuilder();

        if (!$merchantFileImportForm->isValid()) {
            $zedUiFormResponseBuilder->addErrorNotification(
                $this->getFactory()->getTranslatorFacade()->trans(static::MESSAGE_INVALID_FORM_DATA),
            );

            return $this->jsonResponse(array_merge(
                $responseData,
                $zedUiFormResponseBuilder->createResponse()->toArray(true, true),
            ));
        }

        /** @var \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer */
        $merchantFileImportTransfer = $merchantFileImportForm->getData();

        /** @var \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer */
        $merchantFileTransfer = $merchantFileImportForm->get(MerchantFileImportForm::FIELD_MERCHANT_FILE)->getData();

        $merchantFileTransfer->setMerchantFileImport($merchantFileImportTransfer);

        $merchantFileResultTransfer = $this->getFactory()
            ->getMerchantFileFacade()
            ->writeMerchantFile($merchantFileTransfer);

        if ($merchantFileResultTransfer->getIsSuccessful()) {
            $zedUiFormResponseBuilder
                ->addSuccessNotification(
                    $this->getFactory()->getTranslatorFacade()->trans(static::MESSAGE_SUCCESS),
                )
                ->addActionCloseDrawer()
                ->addActionRefreshTable(static::ID_MERCHANT_FILE_IMPORT_TABLE);
        } else {
            $this->addErrorNotifications($zedUiFormResponseBuilder, $merchantFileResultTransfer);
        }

        return $this->jsonResponse(array_merge(
            $responseData,
            $zedUiFormResponseBuilder->createResponse()->toArray(true, true),
        ));
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantFileImportForm
     *
     * @return array<string, mixed>
     */
    protected function getFormViewData(FormInterface $merchantFileImportForm): array
    {
        return [
            'form' => $merchantFileImportForm->createView(),
            'maxFileSize' => $this->getFactory()->getConfig()->getMaxFileSize(),
            'dataImportTemplates' => $this->getFactory()->getConfig()->getDataImportTemplates(),
        ];
    }

    /**
     * @param \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface $zedUiFormResponseBuilder
     * @param \Generated\Shared\Transfer\MerchantFileResultTransfer $merchantFileResultTransfer
     *
     * @return void
     */
    protected function addErrorNotifications(
        ZedUiFormResponseBuilderInterface $zedUiFormResponseBuilder,
        MerchantFileResultTransfer $merchantFileResultTransfer
    ): void {
        foreach ($merchantFileResultTransfer->getMessages() as $messageTransfer) {
            $message = $this->getFactory()->getTranslatorFacade()->trans(
                $messageTransfer->getValueOrFail(),
                $messageTransfer->getParameters(),
            );

            $zedUiFormResponseBuilder->addErrorNotification($message);
        }
    }
}
