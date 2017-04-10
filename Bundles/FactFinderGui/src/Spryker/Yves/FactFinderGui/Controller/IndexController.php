<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinderGui\Controller;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\FactFinderGui\Communication\Plugin\Provider\FactFinderGuiControllerProvider;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\FactFinderGui\FactFinderGuiFactory getFactory()
 * @method \Spryker\Client\FactFinderGui\FactFinderGuiClientInterface getClient()
 */
class IndexController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $factFinderSearchRequestTransfer = $this->getFactory()
            ->createFactFinderSearchRequestTransfer();
        $factFinderSearchRequestTransfer->setQuery($request->query->get('query', '*'));
        $factFinderSearchRequestTransfer->setPage($request->query->get('page'));
        $factFinderSearchRequestTransfer->setSortName($request->query->get('sortName'));
        $factFinderSearchRequestTransfer->setSortPrice($request->query->get('sortPrice'));

        $ffSearchResponseTransfer = $this->getFactory()
            ->getFactFinderClient()
            ->search($factFinderSearchRequestTransfer);

        return [
            'searchResponse' => $ffSearchResponseTransfer,
            'pagingRote' => FactFinderGuiControllerProvider::ROUTE_FACT_FINDER,
            'lang' => Store::getInstance()->getCurrentLanguage(),
            'query' => $factFinderSearchRequestTransfer->getQuery(),
            'page' => $factFinderSearchRequestTransfer->getPage(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function detailAction(Request $request)
    {
        $sku = $request->attributes->get('sku');
        $lang = Store::getInstance()->getCurrentLanguage();
        $locale = $this->getApplication()['locale'];

        $product = $this->getClient()->getProductData($locale, $lang, $sku);
        $categories = $product->getCategory();

        $productData = [
            'product' => $product,
            'productCategories' => $categories,
            'category' => count($categories) ? end($categories) : null,
        ];

        return $productData;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchAction(Request $request)
    {
        $ffSuggestRequestTransfer = $this->getFactory()
            ->createFactFinderSuggestRequestTransfer();
        $query = $request->query->get('query', '*');

        $ffSuggestRequestTransfer->setQuery($query);

        $response = $this->getFactory()
            ->getFactFinderClient()
            ->getSuggestions($ffSuggestRequestTransfer);

        return $this->jsonResponse($response->getSuggestions());
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function recommendationsAction(Request $request)
    {
        $ffSuggestRequestTransfer = $this->getFactory()
            ->createFactFinderRecommendationRequestTransfer();
        $id = $request->query->get('id', '');

        $ffSuggestRequestTransfer->setId($id);

        $response = $this->getFactory()
            ->getFactFinderClient()
            ->getRecommendations($ffSuggestRequestTransfer);

        return $this->jsonResponse($response->getSuggestions());
    }
}
