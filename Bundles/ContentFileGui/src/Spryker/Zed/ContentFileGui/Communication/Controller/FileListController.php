<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ContentFileGui\Communication\ContentFileGuiCommunicationFactory getFactory()
 */
class FileListController extends AbstractController
{
    /**
     * @var string
     */
    public const PARAM_FILE_IDS = 'ids';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function fileListSelectedTableAction(Request $request): JsonResponse
    {
        return $this->jsonResponse(
            $this->getFactory()->createContentFileListSelectedTable($request->query->all(static::PARAM_FILE_IDS))->fetchData(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function fileListViewTableAction(Request $request): JsonResponse
    {
        return $this->jsonResponse(
            $this->getFactory()->createContentFileListViewTable()->fetchData(),
        );
    }
}
