<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Dataset\Business\Exception\DatasetParseException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Dataset\Business\DatasetFacade getFacade()
 * @method \Spryker\Zed\Dataset\Communication\DatasetCommunicationFactory getFactory()
 * @method \Spryker\Zed\Dataset\Persistence\DatasetQueryContainer getQueryContainer()
 */

class EditController extends AbstractController
{
    const URL_PARAM_ID_DATASET = 'id-dataset';
    const MESSAGE_DATASET_PARSE_ERROR = 'Not valid file';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idDataset = $this->castId($request->query->get(static::URL_PARAM_ID_DATASET));
        $form = $this->getFactory()
            ->createDatasetForm($idDataset)
            ->handleRequest($request);
        if ($form->isValid()) {
            $saveRequestTransfer = $form->getData();
            $file = $form->get('contentFile')->getData();
            try {
                $result = $this->getFacade()->save($saveRequestTransfer, $file);
            } catch (DatasetParseException $e) {
                $this->addErrorMessage(static::MESSAGE_DATASET_PARSE_ERROR);
            }
            if (!empty($result)) {
                $redirectUrl = Url::generate('/dataset')->build();

                return $this->redirectResponse($redirectUrl);
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'availableLocales' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
        ]);
    }
}
