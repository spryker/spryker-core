<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinderDemo\Controller;

use Generated\Shared\Transfer\FactFinderSuggestRequestTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\FactFinderDemo\FactFinderDemoFactory getFactory()
 */
class SuggestionsController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $factFinderSuggestRequestTransfer = new FactFinderSuggestRequestTransfer();
        $query = $request->query->get('query', '*');

        $factFinderSuggestRequestTransfer->setQuery($query);

        $response = $this->getFactory()
            ->getFactFinderClient()
            ->getSuggestions($factFinderSuggestRequestTransfer);

        return $this->jsonResponse($response->getSuggestions());
    }

}
