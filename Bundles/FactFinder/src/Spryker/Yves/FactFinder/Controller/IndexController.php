<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinder\Controller;

use Generated\Shared\Transfer\FactFinderSearchRequestTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Application\Controller\AbstractController;
use Spryker\Yves\FactFinder\Communication\Plugin\Provider\FactFinderControllerProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

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
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function detailAction(Request $request)
    {
//        $ffSearchResponseTransfer = $this->getClient()->recommendations();
        $tt=1;
        $sku = $request->attributes->get('sku');
        $lang = Store::getInstance()->getCurrentLanguage();
        $locale = $this->getApplication()['locale'];
//        $url = "/" . $lang . "/" . $sku;


        $product = $this->getClient()->getProductData($locale, $lang, $sku);

        $categories = $product->getCategory();

        $productData = [
            'product' => $product,
            'productCategories' => $categories,
            'category' => count($categories) ? end($categories) : null,
        ];

        return $productData;


//        $app = $this->getApplication();
//        $app = clone $app;

//        $app->register(new \Silex\Provider\SerializerServiceProvider());

//        $ttt = $this->getApplication()->get("/en/tomtom-golf-52");
//        $ttt->
////        $a1 = $ttt->generateRouteName('aaaa');
//        $ttt->detailAction();

//        $a2 = Config::get('fact_finder_basic_auth_username');


//        $subRequest = Request::create($url, 'GET', array(), $request->cookies->all(), array(), $request->server->all());
//        $response = $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);

//        $blockResponse = $this->getApplication()['sub_request']->handleSubRequest($request, $url);
//        $response = $blockResponse->getContent();



        return [];
        return [
            'searchResponse' => $ffSearchResponseTransfer,
            'pagingRote' => FactFinderControllerProvider::ROUTE_FACT_FINDER,
        ];
    }
}
