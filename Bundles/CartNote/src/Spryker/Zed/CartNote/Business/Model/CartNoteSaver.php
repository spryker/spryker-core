<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNote\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CartNote\Persistence\CartNoteEntityManagerInterface;

class CartNoteSaver implements CartNoteSaverInterface
{
    /**
     * @var \Spryker\Zed\CartNote\Persistence\CartNoteEntityManagerInterface
     */
    protected $cartNoteEntityManager;

    /**
     * @param \Spryker\Zed\CartNote\Persistence\CartNoteEntityManagerInterface $cartNoteEntityManager
     */
    public function __construct(CartNoteEntityManagerInterface $cartNoteEntityManager)
    {
        $this->cartNoteEntityManager = $cartNoteEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveCartNoteToOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        if (!$quoteTransfer->getCartNote()) {
            return;
        }

        $this->saveOrderNote($saveOrderTransfer->getIdSalesOrder(), $quoteTransfer->getCartNote());
    }

    /**
     * @param int $idSalesOrder
     * @param string $note
     *
     * @return void
     */
    protected function saveOrderNote($idSalesOrder, $note)
    {
        $this->cartNoteEntityManager->updateOrderNote($idSalesOrder, $note);
    }
}
