<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsBlockGui\Communication\CmsBlockGuiCommunicationFactory getFactory()
 */
class ProductAutocompleteController extends AbstractController
{
    protected const REQUEST_PARAM_SUGGESTION = 'term';
    protected const RESPONSE_KEY_RESULTS = 'results';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $suggestion = $request->query->get(static::REQUEST_PARAM_SUGGESTION, '');

        $productAbstractSuggestions = $this->getFactory()
            ->getProductFacade()
            ->suggestProductAbstract($suggestion);

        return $this->jsonResponse([
            static::RESPONSE_KEY_RESULTS => $this->getFactory()->createProductListFormatter()->prepareData($productAbstractSuggestions),
        ]);
    }
}
