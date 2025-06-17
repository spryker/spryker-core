<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Reader;

use Generated\Shared\Transfer\ItemTransfer;

interface OrderReaderInterface
{
    /**
     * @param int $idSalesOrder
     * @param string $orderItemUuid
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function getOrderItem(int $idSalesOrder, string $orderItemUuid): ItemTransfer;
}
