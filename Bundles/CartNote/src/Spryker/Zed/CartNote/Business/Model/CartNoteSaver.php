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
    protected $cartNotesEntityManager;

    /**
     * CartNotesSaver constructor.
     *
     * @param \Spryker\Zed\CartNote\Persistence\CartNoteEntityManagerInterface $cartNotesEntityManager
     */
    public function __construct(CartNoteEntityManagerInterface $cartNotesEntityManager)
    {
        $this->cartNotesEntityManager = $cartNotesEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveCartNotesToOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
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
        $this->cartNotesEntityManager->updateOrderNote($idSalesOrder, $note);
    }
}
