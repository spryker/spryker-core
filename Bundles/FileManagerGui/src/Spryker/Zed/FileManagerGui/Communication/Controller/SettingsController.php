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
class SettingsController extends AbstractController
{
    public function indexAction()
    {
        $fileTypeSettingsTable = $this->getFactory()
            ->createFileTypeSettingsTable();

        return [
            'fileTypeSettings' => $fileTypeSettingsTable->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createFileTypeSettingsTable();

        return $this->jsonResponse($table->fetchData());
    }
}
