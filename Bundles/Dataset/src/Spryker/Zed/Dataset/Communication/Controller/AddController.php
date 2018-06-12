<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication\Controller;

use Exception;
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

class AddController extends AbstractController
{
    const MESSAGE_DATASET_PARSE_ERROR = 'Something wrong';
    const DATSET_LIST_URL = '/dataset';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $form = $this->getFactory()->getDatasetForm()->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datasetEntityTransfer = $form->getData();
            $file = $form->get('contentFile')->getData();
            $filePathTransfer = new DatasetFilePathTransfer();
            if ($file instanceof UploadedFile) {
                $filePathTransfer->setFilePath($file->getRealPath());
            }
            try {
                $this->getFacade()->save($datasetEntityTransfer, $filePathTransfer);
                $redirectUrl = Url::generate(static::DATSET_LIST_URL)->build();

                return $this->redirectResponse($redirectUrl);
            } catch (DatasetParseException $e) {
                $this->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->addErrorMessage(static::MESSAGE_DATASET_PARSE_ERROR);
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'availableLocales' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
        ]);
    }
}
