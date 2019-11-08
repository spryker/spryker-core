<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\CmsSlotBlockProductCategoryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsSlotBlockProductCategoryGui\Persistence\CmsSlotBlockProductCategoryGuiRepositoryInterface getRepository()
 */
class ProductAutocompleteController extends AbstractController
{
    protected const REQUEST_PARAM_SUGGESTION = 'term';
    protected const REQUEST_PARAM_PAGE = 'page';

    protected const DEFAULT_PAGE = 1;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $suggestion = $request->query->get(static::REQUEST_PARAM_SUGGESTION, '');
        $page = $request->query->getInt(static::REQUEST_PARAM_PAGE, static::DEFAULT_PAGE);

        $productAbstractPaginatedAutocompleteData = $this->getFactory()->createCmsSlotBlockProductCategoryGuiProductReader()
            ->getProductAbstractPaginatedAutocompleteData($suggestion, $page);

        return $this->jsonResponse($productAbstractPaginatedAutocompleteData);
    }
}
