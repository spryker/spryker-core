<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNotes\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CartNotes\Persistence\CartNotesEntityManagerInterface;

class CartNoteSaver implements CartNoteSaverInterface
{
    /**
     * @var \Spryker\Zed\CartNotes\Persistence\CartNotesEntityManagerInterface
     */
    protected $cartNotesEntityManager;

    /**
     * CartNotesSaver constructor.
     *
     * @param \Spryker\Zed\CartNotes\Persistence\CartNotesEntityManagerInterface $cartNotesEntityManager
     */
    public function __construct(CartNotesEntityManagerInterface $cartNotesEntityManager)
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
