<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class DeleteDirectoryController extends AbstractController
{
    const URL_PARAM_ID_DIRECTORY = 'id-directory';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idFileDirectory = $request->get(static::URL_PARAM_ID_DIRECTORY);

        if (!$idFileDirectory) {
            throw new NotFoundHttpException();
        }

        $this->getFactory()
            ->getFileManagerFacade()
            ->deleteFileDirectory($idFileDirectory);

        return $this->redirectResponse(
            Url::generate('/file-manager-gui/directories-tree')->build()
        );
    }
}
