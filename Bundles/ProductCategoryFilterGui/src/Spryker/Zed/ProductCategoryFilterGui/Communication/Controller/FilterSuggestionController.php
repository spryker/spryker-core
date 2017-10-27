<?php

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Controller;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Communication\ProductCategoryFilterGuiCommunicationFactory getFactory()
 */
class FilterSuggestionController extends AbstractController
{
    const PARAM_TERM = 'term';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $searchTerm = $request->query->get(self::PARAM_TERM);

        $suggestions = $this
            ->getFactory()
            ->getProductSearchFacade()
            ->suggestProductSearchAttributeKeys($searchTerm);

        return $this->jsonResponse($suggestions);
    }
}
