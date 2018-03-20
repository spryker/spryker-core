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
     * @return \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer
     */
    public function getData($idCustomer)
    {
        $spyCustomerNoteEntityTransfer = new SpyCustomerNoteEntityTransfer();
        $spyCustomerNoteEntityTransfer->setFkCustomer($idCustomer);

        return $spyCustomerNoteEntityTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => SpyCustomerNoteEntityTransfer::class,
        ];
    }
}
