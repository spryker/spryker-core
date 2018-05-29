<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductAlternativeGui\Business\ProductAlternativeGuiFacadeInterface getFacade()
 */
class ProductNameSuggestionController extends AbstractController
{
    protected const PARAM_NAME = 'name';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $searchName = $request->query->get(static::PARAM_NAME);
        $suggestions = $this
            ->getFacade()
            ->suggestProductNames($searchName);

        return $this->jsonResponse($suggestions);
    }
}
