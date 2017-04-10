<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinderGui\Controller;

use Generated\Shared\Transfer\FactFinderRecommendationRequestTransfer;
use Generated\Shared\Transfer\FactFinderSearchRequestTransfer;
use Generated\Shared\Transfer\FactFinderSuggestRequestTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\FactFinder\Communication\Plugin\Provider\FactFinderControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\FactFinder\FactFinderFactory getFactory()
 * @method \Spryker\Client\FactFinder\FactFinderClientInterface getClient()
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
        $factFinderSearchRequestTransfer = new FactFinderSearchRequestTransfer();
        $factFinderSearchRequestTransfer->setQuery($request->query->get('query', '*'));
        $factFinderSearchRequestTransfer->setPage($request->query->get('page'));
        $factFinderSearchRequestTransfer->setSortName($request->query->get('sortName'));
        $factFinderSearchRequestTransfer->setSortPrice($request->query->get('sortPrice'));

        $ffSearchResponseTransfer = $this->getClient()->search($factFinderSearchRequestTransfer);

        return [
            'searchResponse' => $ffSearchResponseTransfer,
            'pagingRote' => FactFinderControllerProvider::ROUTE_FACT_FINDER,
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
     * @return array
     */
    public function searchAction(Request $request)
    {
        $ffSuggestRequestTransfer = new FactFinderSuggestRequestTransfer();
        $ffSuggestRequestTransfer->setQuery($request->query->get('query', '*'));

        $response = $this->getClient()->getSuggestions($ffSuggestRequestTransfer);

        return $this->jsonResponse($response->getSuggestions());
    }

    public function recommendationsAction(Request $request)
    {
        $ffSuggestRequestTransfer = new FactFinderRecommendationRequestTransfer();
        $ffSuggestRequestTransfer->setId($request->query->get('id', ''));

        $response = $this->getClient()->getRecommendations($ffSuggestRequestTransfer);

        return $this->jsonResponse($response->getSuggestions());
    }
}
