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
        $searchString = $request->query->get('q', '');
        $page = $request->query->get('page');

        $factFinderSearchRequestTransfer = new FactFinderSearchRequestTransfer();
        $factFinderSearchRequestTransfer->setQuery($searchString);
        $factFinderSearchRequestTransfer->setPage($page);

        $ffSearchResponseTransfer = $this->getClient()->search($factFinderSearchRequestTransfer);

        return [
            'searchResponse' => $ffSearchResponseTransfer,
            'pagingRote' => FactFinderControllerProvider::ROUTE_FACT_FINDER,
        ];
    }

}
