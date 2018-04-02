<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Dataset\Business\Exception\DatasetParseException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Dataset\Business\DatasetFacade getFacade()
 * @method \Spryker\Zed\Dataset\Communication\DatasetCommunicationFactory getFactory()
 * @method \Spryker\Zed\Dataset\Persistence\DatasetQueryContainer getQueryContainer()
 */

class AddController extends AbstractController
{
    const MESSAGE_DATASET_PARSE_ERROR = 'Not valid file';
    const DATSET_LIST_URL = '/dataset';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $form = $this->getFactory()->createDatasetForm()->handleRequest($request);

        if ($form->isValid()) {
            $saveRequestTransfer = $form->getData();
            $file = $form->get('contentFile')->getData();
            $filePath = ($file instanceof UploadedFile) ? $file->getRealPath() : null;
            try {
                $this->getFacade()->save($saveRequestTransfer, $filePath);

                $redirectUrl = Url::generate(static::DATSET_LIST_URL)->build();

                return $this->redirectResponse($redirectUrl);
            } catch (DatasetParseException $e) {
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
