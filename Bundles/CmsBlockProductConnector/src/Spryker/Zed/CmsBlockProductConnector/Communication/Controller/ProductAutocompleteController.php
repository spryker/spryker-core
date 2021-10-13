<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Communication\CmsBlockProductConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorRepositoryInterface getRepository()
 */
class ProductAutocompleteController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_SUGGESTION = 'term';
    /**
     * @var string
     */
    protected const REQUEST_PARAM_PAGE = 'page';
    /**
     * @var string
     */
    protected const RESPONSE_KEY_RESULTS = 'results';
    /**
     * @var string
     */
    protected const RESPONSE_KEY_PAGINATION = 'pagination';
    /**
     * @var string
     */
    protected const RESPONSE_KEY_PAGINATION_MORE = 'more';
    /**
     * @var string
     */
    protected const RESPONSE_DATA_KEY_ID = 'id';
    /**
     * @var string
     */
    protected const RESPONSE_DATA_KEY_TEXT = 'text';
    /**
     * @var int
     */
    protected const DEFAULT_PAGE = 1;
    /**
     * @var int
     */
    protected const DEFAULT_ITEMS_PER_PAGE = 10;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $suggestion = $request->query->get(static::REQUEST_PARAM_SUGGESTION, '');
        $page = $request->query->getInt(static::REQUEST_PARAM_PAGE, static::DEFAULT_PAGE);
        $paginationTransfer = $this->getPaginationTransfer($page);

        $productAbstractSuggestionCollectionTransfer = $this->getFactory()
            ->getProductFacade()
            ->getPaginatedProductAbstractSuggestions($suggestion, $paginationTransfer);

        return $this->jsonResponse([
            static::RESPONSE_KEY_RESULTS => $this->transformProductAbstractSuggestionsToAutocompleteData(
                $productAbstractSuggestionCollectionTransfer->getProductAbstracts()
            ),
            static::RESPONSE_KEY_PAGINATION => $this->getPaginationData(
                $productAbstractSuggestionCollectionTransfer->getPagination()
            ),
        ]);
    }

    /**
     * @param int $page
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function getPaginationTransfer(int $page): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setPage($page)
            ->setMaxPerPage(static::DEFAULT_ITEMS_PER_PAGE);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfers
     *
     * @return array
     */
    protected function transformProductAbstractSuggestionsToAutocompleteData(ArrayObject $productAbstractTransfers): array
    {
        $autocompleteData = [];
        $productLabelFormatter = $this->getFactory()->createProductLabelFormatter();

        foreach ($productAbstractTransfers as $productAbstractTransfer) {
            $autocompleteData[] = [
                static::RESPONSE_DATA_KEY_ID => $productAbstractTransfer->getIdProductAbstract(),
                static::RESPONSE_DATA_KEY_TEXT => $productLabelFormatter->format(
                    $productAbstractTransfer->getName(),
                    $productAbstractTransfer->getSku()
                ),
            ];
        }

        return $autocompleteData;
    }

    /**
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return array
     */
    protected function getPaginationData(PaginationTransfer $paginationTransfer): array
    {
        $hasMoreResults = $paginationTransfer->getLastPage() > 0 &&
            $paginationTransfer->getLastPage() !== $paginationTransfer->getPage();

        return [
            static::RESPONSE_KEY_PAGINATION_MORE => $hasMoreResults,
        ];
    }
}
