<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Controller;

use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\FacetResultFormatterPlugin;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Communication\ProductCategoryFilterGuiCommunicationFactory getFactory()
 */
class FilterSuggestionController extends AbstractController
{
    const PARAM_TERM = 'term';
    const PARAM_CATEGORY = 'category';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $searchTerm = $request->query->get(self::PARAM_TERM);
        $idCategory = $request->query->get(self::PARAM_CATEGORY);

        $suggestions = $this
            ->getFactory()
            ->getProductSearchFacade()
            ->suggestProductSearchAttributeKeys($searchTerm);

        $searchResultsForCategory = $this->getFactory()
            ->getCatalogClient()
            ->catalogSearch('', [PageIndexMap::CATEGORY => $idCategory]);

        $suggestionsWithNumbers = [];
        foreach ($suggestions as $suggestion) {
            $attachedNumber = 0;
            if (isset($searchResultsForCategory[FacetResultFormatterPlugin::NAME][$suggestion])) {
                $attachedNumber = $searchResultsForCategory[FacetResultFormatterPlugin::NAME][$suggestion]->getDocCount();
            }

            $suggestionsWithNumbers[] = $suggestion . ' (' . $attachedNumber . ')';
        }

        return $this->jsonResponse($suggestionsWithNumbers);
    }
}
