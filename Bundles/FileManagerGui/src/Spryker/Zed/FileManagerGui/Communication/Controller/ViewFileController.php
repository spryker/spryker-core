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
class ViewFileController extends AbstractController
{
    protected const URL_PARAM_ID_FILE = 'id-file';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idFile = $this->castId($request->get(static::URL_PARAM_ID_FILE));

        $file = $this->getFactory()
            ->getFileManagerFacade()
            ->findFileByIdFile($idFile)
            ->getFile();
        $fileInfoTable = $this->getFactory()->createFileInfoViewTable($idFile);

        return [
            'file' => $file,
            'fileInfoTable' => $fileInfoTable->render(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function fileInfoTableAction(Request $request)
    {
        $idFile = $this->castId(
            $request->get(static::URL_PARAM_ID_FILE)
        );

        $fileInfoTable = $this
            ->getFactory()
            ->createFileInfoViewTable($idFile);

        return $this->jsonResponse(
            $fileInfoTable->fetchData()
        );
    }
}
