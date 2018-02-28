<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNoteGui\Communication\Handler;

use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToCustomerNoteFacadeInterface;
use Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToUserFacadeInterface;

class NoteHandler implements NoteHandlerInterface
{
    protected const USERNAME_FORMAT = '%s %s';

    /**
     * @var \Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToCustomerNoteFacadeInterface
     */
    protected $customerNoteFacade;

    /**
     * @param \Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToCustomerNoteFacadeInterface $customerNoteFacade
     */
    public function __construct(CustomerNoteGuiToUserFacadeInterface $userFacade, CustomerNoteGuiToCustomerNoteFacadeInterface $customerNoteFacade)
    {
        $this->userFacade = $userFacade;
        $this->customerNoteFacade = $customerNoteFacade;
    }

    /**
     * @param int $idCustomer
     * @param string $note
     *
     * @return \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer
     */
    public function handleNoteAddition(int $idCustomer, string $note)
    {
        $commentTransfer = new SpyCustomerNoteEntityTransfer();
        $commentTransfer->setMessage($note);
        $commentTransfer->setFkCustomer($idCustomer);
        $currentUserTransfer = $this->userFacade->getCurrentUser();
        $commentTransfer->setUsername(
            sprintf(self::USERNAME_FORMAT, $currentUserTransfer->getFirstName(), $currentUserTransfer->getLastName())
        );

        return $this->customerNoteFacade->addNote($commentTransfer);
    }
}
