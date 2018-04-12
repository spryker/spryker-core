<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();

        $fileDirectoryTreeTransfer = $this->getFactory()
            ->getFileManagerFacade()
            ->findFileDirectoryTree($localeTransfer);

        $fileTable = $this->getFactory()
            ->createFileTable();

        return [
            'files' => $fileTable->render(),
            'fileDirectoryTree' => $fileDirectoryTreeTransfer,
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createFileTable();

        return $this->jsonResponse($table->fetchData());
    }
}
