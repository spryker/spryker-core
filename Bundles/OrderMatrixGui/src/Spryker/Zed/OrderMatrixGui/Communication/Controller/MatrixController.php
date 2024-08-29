<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrixGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\OrderMatrixGui\Communication\OrderMatrixGuiCommunicationFactory getFactory()
 */
class MatrixController extends AbstractController
{
    /**
     * @var string
     */
    protected const FIELD_MATRIX = 'matrix';

    /**
     * @return array<string, array<int, array<string>>>
     */
    public function indexAction(): array
    {
        $orderMatrix = $this->getFactory()
            ->getOrderMatrixFacade()
            ->getOrderMatrixStatistics()
            ->getMatrices();
        $formattedOrderMatrix = $this->getFactory()
            ->createOrderMatrixFormatter()
            ->formatOrderMatrix($orderMatrix);

        return [
            static::FIELD_MATRIX => $formattedOrderMatrix,
        ];
    }
}
