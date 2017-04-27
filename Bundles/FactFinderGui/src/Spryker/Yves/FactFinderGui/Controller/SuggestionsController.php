<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinderGui\Controller;

use Generated\Shared\Transfer\FactFinderSuggestRequestTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\FactFinderGui\FactFinderGuiFactory getFactory()
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
        $ffSuggestRequestTransfer = new FactFinderSuggestRequestTransfer();
        $query = $request->query->get('query', '*');

        $ffSuggestRequestTransfer->setQuery($query);

        $response = $this->getFactory()
            ->getFactFinderClient()
            ->getSuggestions($ffSuggestRequestTransfer);

        return $this->jsonResponse($response->getSuggestions());
    }

}
