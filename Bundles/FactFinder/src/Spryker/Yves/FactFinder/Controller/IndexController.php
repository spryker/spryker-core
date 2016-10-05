<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinder\Controller;

use Generated\Shared\Transfer\FactFinderSearchRequestTransfer;
use Spryker\Yves\Application\Controller\AbstractController;
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
        $factFinderSearchRequestTransfer->setQuery($request->query->get('query', ''));
        $factFinderSearchRequestTransfer->setPage($request->query->get('page'));
        $factFinderSearchRequestTransfer->setSortName($request->query->get('sortName'));
        $factFinderSearchRequestTransfer->setSortPrice($request->query->get('sortPrice'));

        $ffSearchResponseTransfer = $this->getClient()->search($factFinderSearchRequestTransfer);

        return [
            'searchResponse' => $ffSearchResponseTransfer,
            'pagingRote' => FactFinderControllerProvider::ROUTE_FACT_FINDER,
        ];
    }

}
