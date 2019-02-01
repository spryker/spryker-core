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
    protected const PARAM_NAME = 'term';
    protected const KEY_RESULTS = 'results';
    protected const KEY_ID = 'id';
    protected const KEY_TEXT = 'text';
    protected const TEXT_FORMAT = '%s (sku: %s)';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $suggestion = $request->query->get(static::PARAM_NAME, '');

        $productAbstractSuggestions = $this->getFactory()
            ->getCmsBlockProductConnectorFacade()
            ->suggestProductAbstract($suggestion);

        return $this->jsonResponse([
            static::KEY_RESULTS => $this->prepareData($productAbstractSuggestions),
        ]);
    }

    /**
     * @param array $suggestData
     *
     * @return array
     */
    protected function prepareData(array $suggestData): array
    {
        $preparedSuggestData = [];
        foreach ($suggestData as $sku => $name) {
            $preparedSuggestData[] = [
                static::KEY_ID => $sku,
                static::KEY_TEXT => sprintf(static::TEXT_FORMAT, $name, $sku),
            ];
        }

        return $preparedSuggestData;
    }
}
