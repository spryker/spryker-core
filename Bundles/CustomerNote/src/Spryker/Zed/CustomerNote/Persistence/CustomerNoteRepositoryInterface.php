<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Persistence;

use Generated\Shared\Transfer\CustomerNoteCollectionTransfer;

interface CustomerNoteRepositoryInterface
{
    /**
     * Specification:
     * - Fetches customer notes by id customer
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerNoteCollectionTransfer
     */
    public function getCustomerNoteCollectionByIdCustomer(int $idCustomer): CustomerNoteCollectionTransfer;
}
