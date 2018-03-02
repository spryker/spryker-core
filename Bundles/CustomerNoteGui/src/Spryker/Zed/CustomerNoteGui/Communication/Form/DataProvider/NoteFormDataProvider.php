<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNoteGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;

class NoteFormDataProvider
{
    /**
     * @param int $idCustomer
     *
     * @return array
     */
    public function getData($idCustomer)
    {
        return [
            SpyCustomerNoteEntityTransfer::MESSAGE => '',
            SpyCustomerNoteEntityTransfer::FK_CUSTOMER => $idCustomer,
        ];
    }
}
