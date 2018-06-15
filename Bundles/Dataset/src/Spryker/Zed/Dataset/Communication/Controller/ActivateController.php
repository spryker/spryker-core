<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication\Controller;

use Generated\Shared\Transfer\DatasetTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Dataset\Business\DatasetFacade getFacade()
 * @method \Spryker\Zed\Dataset\Communication\DatasetCommunicationFactory getFactory()
 */

class ActivateController extends AbstractController
{
    const URL_PARAM_ID_DATASET = 'id-dataset';
    const URL_PARAM_REDIRECT_URL = 'redirect-url';
    const REDIRECT_URL_DEFAULT = '/dataset';
    const REFERER_PARAM = 'referer';

    const MESSAGE_DATASET_ACTIVATE_SUCCESS = 'Dataset was activated successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idDataset = $this->castId($request->query->get(static::URL_PARAM_ID_DATASET));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);
        $this->getFacade()->activateDataset((new DatasetTransfer())->setIdDataset($idDataset)->setIsActive(true));
        $this->addSuccessMessage(static::MESSAGE_DATASET_ACTIVATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectBack(Request $request)
    {
        $referer = $request->headers->get(static::REFERER_PARAM);

        return $this->redirectResponse($referer);
    }
}
