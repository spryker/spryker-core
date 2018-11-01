<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNote\Persistence;

interface CartNoteEntityManagerInterface
{
    /**
     * @param int $idSalesOrder
     * @param string $note
     *
     * @return void
     */
    public function updateOrderNote($idSalesOrder, $note);
}
