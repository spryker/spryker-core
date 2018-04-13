<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNote\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CartNote\Persistence\CartNotePersistenceFactory getFactory()
 */
class CartNoteEntityManager extends AbstractEntityManager implements CartNoteEntityManagerInterface
{
    /**
     * @param int $idSalesOrder
     * @param string $note
     *
     * @return void
     */
    public function updateOrderNote($idSalesOrder, $note)
    {
        $salesOrderEntity = $this->getFactory()->getSalesOrderQuery()
            ->findOneByIdSalesOrder($idSalesOrder);
        $salesOrderEntity->setCartNote($note);
        $salesOrderEntity->save();
    }
}
