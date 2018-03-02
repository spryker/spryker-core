<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNoteGui\Communication\Handler;

use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;

interface NoteHandlerInterface
{
    /**
     * @param int $idCustomer
     * @param string $note
     *
     * @return \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer
     */
    public function handleNoteAddition(int $idCustomer, string $note): SpyCustomerNoteEntityTransfer;
}
