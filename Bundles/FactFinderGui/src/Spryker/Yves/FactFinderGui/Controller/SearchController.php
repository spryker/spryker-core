<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinderGui\Controller;

use Generated\Shared\Transfer\FactFinderSearchRequestTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\FactFinderGui\FactFinderGuiFactory getFactory()
 */
class SearchController extends AbstractController
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

        $ffSearchResponseTransfer = $this->getFactory()
            ->getFactFinderClient()
            ->search($factFinderSearchRequestTransfer);

        return [
            'searchResponse' => $ffSearchResponseTransfer,
            'pagingRote' => 'fact-finder',
            'lang' => Store::getInstance()->getCurrentLanguage(),
            'query' => $factFinderSearchRequestTransfer->getQuery(),
            'page' => $factFinderSearchRequestTransfer->getPage(),
        ];
    }

}
