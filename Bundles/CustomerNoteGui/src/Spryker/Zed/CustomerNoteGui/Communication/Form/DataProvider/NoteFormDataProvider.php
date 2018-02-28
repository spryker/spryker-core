<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNoteGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Spryker\Zed\CustomerNoteGui\Communication\Form\NoteForm;
use Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToCustomerNoteFacadeInterface;

class NoteFormDataProvider
{
    /**
     * @var \Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToCustomerNoteFacadeInterface
     */
    protected $customerNoteFacade;

    /**
     * @param \Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToCustomerNoteFacadeInterface $customerNoteFacade
     */
    public function __construct(CustomerNoteGuiToCustomerNoteFacadeInterface $customerNoteFacade)
    {
        $this->customerNoteFacade = $customerNoteFacade;
    }

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

    /**
     * @param int $idCustomer
     *
     * @return array
     */
    public function getOptions($idCustomer)
    {
        return [NoteForm::CUSTOMER_NOTES_OPTION => $this->customerNoteFacade->getNotes($idCustomer)];
    }
}
