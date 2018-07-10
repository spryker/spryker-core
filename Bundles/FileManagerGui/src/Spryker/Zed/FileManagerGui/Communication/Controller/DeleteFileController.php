<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class DeleteFileController extends AbstractController
{
    protected const URL_REDIRECT_BASE = '/file-manager-gui';
    protected const URL_PARAM_ID_FILE_INFO = 'id-file-info';
    protected const URL_PARAM_ID_FILE = 'id-file';
    protected const REFERER_PARAM = 'referer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function fileInfoAction(Request $request)
    {
        $idFile = $request->get(static::URL_PARAM_ID_FILE);
        $idFileInfo = $request->get(static::URL_PARAM_ID_FILE_INFO);

        $this->getFactory()
            ->getFileManagerFacade()
            ->deleteFileInfo($idFileInfo);

        $redirectUrl = sprintf(static::URL_REDIRECT_BASE . '/edit-file?id-file=%s', $idFile);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function fileAction(Request $request)
    {
        $idFile = $request->get(static::URL_PARAM_ID_FILE);

        $this->getFactory()
            ->getFileManagerFacade()
            ->deleteFile($idFile);

        return $this->redirectResponse(static::URL_REDIRECT_BASE);
    }
}
