<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication\Controller;

use Generated\Shared\Transfer\DatasetTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Dataset\Business\DatasetFacadeInterface getFacade()
 * @method \Spryker\Zed\Dataset\Communication\DatasetCommunicationFactory getFactory()
 * @method \Spryker\Zed\Dataset\Persistence\DatasetRepositoryInterface getRepository()
 */
class DeleteController extends AbstractController
{
    protected const URL_PARAM_ID_DATASET = 'id-dataset';
    protected const REFERER_PARAM = 'referer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idDataset = $this->castId($request->get(static::URL_PARAM_ID_DATASET));
        $this->getFacade()->delete((new DatasetTransfer())->setIdDataset($idDataset));

        return $this->redirectBack($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectBack(Request $request): RedirectResponse
    {
        $referer = $request->headers->get(static::REFERER_PARAM);

        return $this->redirectResponse($referer);
    }
}
