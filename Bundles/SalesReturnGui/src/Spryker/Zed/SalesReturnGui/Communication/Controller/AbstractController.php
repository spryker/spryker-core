<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Controller;

use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController as SprykerAbstractController;

/**
 * @method \Spryker\Zed\SalesReturnGui\Communication\SalesReturnGuiCommunicationFactory getFactory()
 */
class AbstractController extends SprykerAbstractController
{
    /**
     * @param int $idSalesReturn
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer|null
     */
    protected function findReturn(int $idSalesReturn): ?ReturnTransfer
    {
        $returnCollectionTransfer = $this->getFactory()->getSalesReturnFacade()->getReturns(
            (new ReturnFilterTransfer())->addIdReturn($idSalesReturn)
        );

        if (!$returnCollectionTransfer->getReturns()->count()) {
            return null;
        }

        /** @var \Generated\Shared\Transfer\ReturnTransfer $returnTransfer */
        $returnTransfer = $returnCollectionTransfer->getReturns()->getIterator()->current();

        return $returnTransfer;
    }
}
