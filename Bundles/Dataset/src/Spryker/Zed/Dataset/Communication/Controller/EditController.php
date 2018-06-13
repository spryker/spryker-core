<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication\Controller;

use Generated\Shared\Transfer\DatasetFilePathTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Dataset\Business\Exception\DatasetParseException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Dataset\Business\DatasetFacade getFacade()
 * @method \Spryker\Zed\Dataset\Communication\DatasetCommunicationFactory getFactory()
 */

class EditController extends AbstractController
{
    const URL_PARAM_ID_DATASET = 'id-dataset';
    const MESSAGE_DATASET_PARSE_ERROR = 'Something wrong';
    const DATSET_LIST_URL = '/dataset';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idDataset = $this->castId($request->query->get(static::URL_PARAM_ID_DATASET));
        $form = $this->getFactory()->getDatasetForm($idDataset)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datasetEntityTransfer = $form->getData();
            $file = $form->get('contentFile')->getData();
            try {
                $this->saveDataset($datasetEntityTransfer, $file);
                $redirectUrl = Url::generate(static::DATSET_LIST_URL)->build();

                return $this->redirectResponse($redirectUrl);
            } catch (DatasetParseException $e) {
                $this->addErrorMessage($e->getMessage());
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'availableLocales' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     * @param string $file
     *
     * @return void
     */
    protected function saveDataset($datasetEntityTransfer, $file)
    {
        if ($file instanceof UploadedFile) {
            $filePathTransfer = new DatasetFilePathTransfer();
            $filePathTransfer->setFilePath($file->getRealPath());
            $this->getFacade()->save($datasetEntityTransfer, $filePathTransfer);

            return;
        }

        $this->getFacade()->saveDataset($datasetEntityTransfer);
    }
}
