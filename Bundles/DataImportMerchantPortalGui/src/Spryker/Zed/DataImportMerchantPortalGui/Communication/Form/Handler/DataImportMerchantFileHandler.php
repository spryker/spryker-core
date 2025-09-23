<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\Handler;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileInfoTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Controller\FilesController;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\DataImportMerchantFileForm;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\DataImportMerchantFileInfoForm;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToDataImportMerchantFacadeInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToGlossaryFacadeInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToTranslatorFacadeInterface;
use Symfony\Component\Form\FormInterface;

class DataImportMerchantFileHandler
{
    /**
     * @var string
     */
    protected const MESSAGE_SUCCESS = 'File import has been started';

    /**
     * @param \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToDataImportMerchantFacadeInterface $dataImportMerchantFacade
     * @param \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        protected DataImportMerchantPortalGuiToDataImportMerchantFacadeInterface $dataImportMerchantFacade,
        protected DataImportMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        protected DataImportMerchantPortalGuiToGlossaryFacadeInterface $glossaryFacade
    ) {
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $dataImportMerchantFileForm
     * @param \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface $zedUiFormResponseBuilder
     *
     * @return \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface
     */
    public function handleDataImportMerchantFileCreation(
        FormInterface $dataImportMerchantFileForm,
        ZedUiFormResponseBuilderInterface $zedUiFormResponseBuilder
    ): ZedUiFormResponseBuilderInterface {
        $dataImportMerchantFileTransfer = $this->createDataImportMerchantFileTransfer($dataImportMerchantFileForm);
        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer)
            ->setIsTransactional(true);

        $dataImportMerchantFileCollectionResponseTransfer = $this->dataImportMerchantFacade
            ->createDataImportMerchantFileCollection($dataImportMerchantFileCollectionRequestTransfer);

        if ($dataImportMerchantFileCollectionResponseTransfer->getErrors()->count()) {
            $translatedErrors = $this->translateGlossaryKeys($dataImportMerchantFileCollectionResponseTransfer);
            foreach ($translatedErrors as $errorMessage) {
                $zedUiFormResponseBuilder->addErrorNotification($errorMessage);
            }

            return $zedUiFormResponseBuilder;
        }

        return $zedUiFormResponseBuilder
            ->addSuccessNotification($this->translatorFacade->trans(static::MESSAGE_SUCCESS))
            ->addActionCloseDrawer()
            ->addActionRefreshTable(FilesController::ID_DATA_IMPORT_MERCHANT_FILE_TABLE);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $dataImportMerchantFileForm
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    protected function createDataImportMerchantFileTransfer(
        FormInterface $dataImportMerchantFileForm
    ): DataImportMerchantFileTransfer {
        $uploadedFile = $dataImportMerchantFileForm->get(DataImportMerchantFileForm::FIELD_FILE_INFO)
            ->get(DataImportMerchantFileInfoForm::FIELD_FILE)
            ->getData();

        /** @var string $fileContent */
        $fileContent = file_get_contents($uploadedFile->getPathname());

        $dataImportMerchantFileInfoTransfer = (new DataImportMerchantFileInfoTransfer())
            ->setOriginalFileName($uploadedFile->getClientOriginalName())
            ->setSize($uploadedFile->getSize())
            ->setContentType($uploadedFile->getMimeType())
            ->setRealPath($uploadedFile->getRealPath())
            ->setContent($fileContent);

        /** @var \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer */
        $dataImportMerchantFileTransfer = $dataImportMerchantFileForm->getData();
        $dataImportMerchantFileTransfer->setFileInfo($dataImportMerchantFileInfoTransfer);

        return $dataImportMerchantFileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
     *
     * @return list<string>
     */
    protected function translateGlossaryKeys(DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer): array
    {
        $translatedErrors = [];
        foreach ($dataImportMerchantFileCollectionResponseTransfer->getErrors() as $errorTransfer) {
            $translatedErrors[] = $this->glossaryFacade->translate(
                $errorTransfer->getMessageOrFail(),
                $errorTransfer->getParameters(),
            );
        }

        return $translatedErrors;
    }
}
