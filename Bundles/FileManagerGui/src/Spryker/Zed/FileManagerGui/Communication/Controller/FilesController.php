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
class FilesController extends AbstractController
{
    protected const FILE_DIRECTORY_ID = 'file-directory-id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $fileDirectoryId = $request->request->getInt(static::FILE_DIRECTORY_ID) ?: null;
        
        $fileTable = $this->getFactory()
            ->createFileTable($fileDirectoryId);

        return [
            'files' => $fileTable->render(),
            'fileDirectoryId' => $fileDirectoryId,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request)
    {
        $table = $this->getFactory()
            ->createFileTable($request->get(static::FILE_DIRECTORY_ID));

        return $this->jsonResponse($table->fetchData());
    }
}
