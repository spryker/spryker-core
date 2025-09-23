<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Controller;

use Spryker\Zed\DataImportMerchantPortalGui\DataImportMerchantPortalGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\DataImportMerchantPortalGui\Communication\DataImportMerchantPortalGuiCommunicationFactory getFactory()
 */
class ImportController extends AbstractController
{
    /**
     * @var string
     */
    protected const TEMPLATE_FORM_START_IMPORT = 'DataImportMerchantPortalGui/Partials/start-import.twig';

    /**
     * @var string
     */
    protected const MESSAGE_INVALID_FORM_DATA = 'Invalid form data';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): Response
    {
        $dataImportMerchantFileForm = $this->getFactory()
            ->createDataImportMerchantFileForm()
            ->handleRequest($request);

        $formViewResponse = $this->renderView(
            static::TEMPLATE_FORM_START_IMPORT,
            [
                'form' => $dataImportMerchantFileForm->createView(),
                'maxFileSize' => DataImportMerchantPortalGuiConfig::MAX_FILE_SIZE_MB,
                'dataImportTemplates' => $this->getFactory()->getConfig()->getDataImportTemplates(),
            ],
        );

        if (!$dataImportMerchantFileForm->isSubmitted()) {
            return $this->jsonResponse(['form' => $formViewResponse->getContent()]);
        }

        $zedUiFormResponseBuilder = $this->getFactory()->getZedUiFactory()->createZedUiFormResponseBuilder();

        if (!$dataImportMerchantFileForm->isValid()) {
            if (!$dataImportMerchantFileForm->getErrors(true)->count()) {
                $zedUiFormResponseBuilder->addErrorNotification(
                    $this->getFactory()->getTranslatorFacade()->trans(static::MESSAGE_INVALID_FORM_DATA),
                );
            }

            return $this->jsonResponse(array_merge(
                ['form' => $formViewResponse->getContent()],
                $zedUiFormResponseBuilder->createResponse()->toArray(true, true),
            ));
        }

        $zedUiFormResponseBuilder = $this->getFactory()
            ->createDataImportMerchantFileHandler()
            ->handleDataImportMerchantFileCreation($dataImportMerchantFileForm, $zedUiFormResponseBuilder);

        return $this->jsonResponse(array_merge(
            ['form' => $formViewResponse->getContent()],
            $zedUiFormResponseBuilder->createResponse()->toArray(true, true),
        ));
    }
}
