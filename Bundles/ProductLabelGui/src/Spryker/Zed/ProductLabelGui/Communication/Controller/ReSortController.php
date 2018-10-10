<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductLabelGui\Business\ProductLabelGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabelGui\Communication\ProductLabelGuiCommunicationFactory getFactory()
 */
class ReSortController extends AbstractController
{
    public const PARAM_SORT_ORDER_DATA = 'sort-order-data';

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
            ->findAllLabels();
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
        $this->getFacade()->updateLabelPositions(
            $productLabelTransferCollection,
            $this->getLabelPositionMap($sortOrderData)
        );

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Product label priority updated successfully.',
        ]);
    }

    /**
     * @param array $sortOrderData
     *
     * @return int[]
     */
    protected function getLabelPositionMap($sortOrderData)
    {
        $positionMap = [];

        foreach ($sortOrderData as $idProductLabel => $positionData) {
            $positionMap[$idProductLabel] = $positionData['position'];
        }

        return $positionMap;
    }
}
