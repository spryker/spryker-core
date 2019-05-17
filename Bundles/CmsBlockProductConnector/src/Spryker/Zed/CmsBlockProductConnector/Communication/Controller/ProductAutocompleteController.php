<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Communication\CmsBlockProductConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorRepositoryInterface getRepository()
 */
class ProductAutocompleteController extends AbstractController
{
    protected const REQUEST_PARAM_SUGGESTION = 'term';
    protected const RESPONSE_KEY_RESULTS = 'results';
    protected const RESPONSE_DATA_KEY_ID = 'id';
    protected const RESPONSE_DATA_KEY_TEXT = 'text';

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
            static::RESPONSE_KEY_RESULTS => $this->transformProductAbstractSuggestionsToAutocompleteData($productAbstractSuggestions),
        ]);
    }

    /**
     * @param string[] $productAbstractSuggestions
     *
     * @return array
     */
    protected function transformProductAbstractSuggestionsToAutocompleteData(array $productAbstractSuggestions): array
    {
        $autocompleteData = [];
        $productLabelFormatter = $this->getFactory()->createProductLabelFormatter();

        foreach ($productAbstractSuggestions as $sku => $name) {
            $autocompleteData[] = [
                static::RESPONSE_DATA_KEY_ID => $sku,
                static::RESPONSE_DATA_KEY_TEXT => $productLabelFormatter->format($name, $sku),
            ];
        }

        return $autocompleteData;
    }
}
