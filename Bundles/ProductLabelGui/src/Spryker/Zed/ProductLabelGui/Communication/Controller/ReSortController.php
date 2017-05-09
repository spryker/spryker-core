<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductLabelGui\Communication\ProductLabelGuiCommunicationFactory getFactory()
 */
class ReSortController extends AbstractController
{

    const PARAM_SORT_ORDER_DATA = 'sort-order-data';

    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->viewResponse([
            'productLabelTransferCollection' => $this->findAllProductLabelsSorted(),
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    protected function findAllProductLabelsSorted()
    {
        return $this
            ->getFactory()
            ->getProductLabelFacade()
            ->readAllLabels();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveAction(Request $request)
    {
        $sortOrderData = $request->request->get(static::PARAM_SORT_ORDER_DATA);

        if (!$sortOrderData) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Missing or wrong request data',
            ]);
        }

        $productLabelTransferCollection = $this->findAllProductLabelsSorted();
        $productLabelTransferCollection = $this->updatePositions($productLabelTransferCollection, $sortOrderData);
        $this->storeUpdatedPositions($productLabelTransferCollection);

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Product label priority updated successfully.',
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer[] $productLabelTransferCollection
     * @param array $sortOrderData
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    protected function updatePositions(array $productLabelTransferCollection, array $sortOrderData)
    {
        foreach ($productLabelTransferCollection as $productLabelTransfer) {
            $position = $sortOrderData[$productLabelTransfer->getIdProductLabel()]['position'];
            $productLabelTransfer->setPosition($position);
        }

        return $productLabelTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer[] $productLabelTransferCollection
     *
     * @return void
     */
    protected function storeUpdatedPositions(array $productLabelTransferCollection)
    {
        foreach ($productLabelTransferCollection as $productLabelTransfer) {
            $this
                ->getFactory()
                ->getProductLabelFacade()
                ->updateLabel($productLabelTransfer);
        }
    }

}
