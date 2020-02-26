<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SearchElasticsearchGui\Communication\SearchElasticsearchGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SearchElasticsearchGui\Business\SearchElasticsearchGuiFacadeInterface getFacade()
 */
class MaintenanceController extends AbstractController
{
    public const URL_PARAM_INDEX = 'index';
    public const URL_PARAM_DOCUMENT_ID = 'documentId';

    /**
     * @return array
     */
    public function listIndexesAction(): array
    {
        $indexTable = $this->getFactory()->createIndexTable();

        return $this->viewResponse(['indexTable' => $indexTable->render()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listIndexesAjaxAction(): JsonResponse
    {
        $indexTable = $this->getFactory()->createIndexTable();

        return $this->jsonResponse(
            $indexTable->fetchData()
        );
    }

    /**
     * @return array
     */
    public function listDocumentsAction(): array
    {
        $documentTable = $this->getFactory()->createDocumentTable();

        return $this->viewResponse(['documentTable' => $documentTable->render()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listDocumentsAjaxAction(): JsonResponse
    {
        $documentTable = $this->getFactory()->createDocumentTable();

        return $this->jsonResponse(
            $documentTable->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function documentInfoAction(Request $request): array
    {
        $documentId = $request->get(static::URL_PARAM_DOCUMENT_ID);
        $indexName = $request->get(static::URL_PARAM_INDEX);

        $document = $this->getFacade()->readDocument($documentId, $indexName);

        return $this->viewResponse([
            'data' => var_export($document->getData(), true),
            'documentId' => $documentId,
            'indexName' => $indexName,
        ]);
    }
}
