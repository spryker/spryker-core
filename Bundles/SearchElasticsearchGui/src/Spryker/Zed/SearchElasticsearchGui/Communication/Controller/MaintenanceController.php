<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Communication\Controller;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SearchElasticsearchGui\Communication\SearchElasticsearchGuiCommunicationFactory getFactory()
 */
class MaintenanceController extends AbstractController
{
    public const URL_PARAM_INDEX = 'index';
    public const URL_PARAM_TYPE = 'type';

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexInfoAction(Request $request): array
    {
        $indexName = $request->query->get(static::URL_PARAM_INDEX);
        $elasticsearchContextTransfer = $this->createElasticsearchContextTransferFromIndexName($indexName);
        $searchElasticsearchFacade = $this->getFactory()->getSearchElasticsearchFacade();

        return $this->viewResponse([
            'indexName' => $indexName,
            'totalCount' => $searchElasticsearchFacade->getDocumentsTotalCount($elasticsearchContextTransfer),
            'metaData' => $searchElasticsearchFacade->getDocumentsTotalCount($elasticsearchContextTransfer),
        ]);
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
        $documentId = $request->get('documentId');
        $typeName = $request->get('type');
        $indexName = $request->get('index');
        $searchDocumentTransfer = $this->createSearchDocumentTransfer($documentId, $indexName, $typeName);

        $document = $this->getFactory()->getSearchElasticsearchClient()->readDocument($searchDocumentTransfer);

        return $this->viewResponse([
            'data' => var_export($document->getData(), true),
            'documentId' => $documentId,
            'indexName' => $indexName,
            'typeName' => $typeName,
        ]);
    }

    /**
     * @param string $indexName
     * @param string|null $typeName
     *
     * @return \Generated\Shared\Transfer\ElasticsearchSearchContextTransfer
     */
    protected function createElasticsearchContextTransferFromIndexName(string $indexName, ?string $typeName = null): ElasticsearchSearchContextTransfer
    {
        return (new ElasticsearchSearchContextTransfer())
            ->setIndexName($indexName)
            ->setTypeName($typeName);
    }

    /**
     * @param string $documentId
     * @param string $indexName
     * @param string $typeName
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    protected function createSearchDocumentTransfer(string $documentId, string $indexName, string $typeName): SearchDocumentTransfer
    {
        $elasticsearchContextTransfer = $this->createElasticsearchContextTransferFromIndexName($indexName, $typeName);
        $searchContextTransfer = (new SearchContextTransfer())->setElasticsearchContext($elasticsearchContextTransfer);
        $searchDocumentTransfer = (new SearchDocumentTransfer())->setId($documentId)->setSearchContext($searchContextTransfer);

        return $searchDocumentTransfer;
    }
}
