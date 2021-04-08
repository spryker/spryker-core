<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Controller;

use Generated\Shared\Transfer\StoreWithStateCollectionTransfer;
use Generated\Shared\Transfer\StoreWithStateTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 */
class SearchController extends AbstractController
{
    protected const REQUEST_PARAM_ID_CATEGORY_NODE = 'id-category-node';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function categoryStoreAction(Request $request): JsonResponse
    {
        $idCategoryNode = $this->castId($request->query->get(static::REQUEST_PARAM_ID_CATEGORY_NODE));

        $storeWithStateCollectionTransfer = $this->getFactory()
            ->createCategoryStoreWithSateFinder()
            ->getAllStoresWithStateByIdCategoryNode($idCategoryNode);

        return $this->jsonResponse(
            $this->extractStoresWithStateFromCollection($storeWithStateCollectionTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreWithStateCollectionTransfer $storeWithStateCollectionTransfer
     *
     * @return array
     */
    protected function extractStoresWithStateFromCollection(StoreWithStateCollectionTransfer $storeWithStateCollectionTransfer): array
    {
        return array_map(function (StoreWithStateTransfer $storeWithStateTransfers) {
            return $storeWithStateTransfers->toArray();
        }, $storeWithStateCollectionTransfer->getStores()->getArrayCopy());
    }
}
