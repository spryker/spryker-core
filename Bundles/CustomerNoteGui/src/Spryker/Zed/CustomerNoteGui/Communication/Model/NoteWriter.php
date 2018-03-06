<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNoteGui\Communication\Model;

use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\CustomerNoteGui\Dependency\Facade\CustomerNoteGuiToCustomerNoteFacadeInterface;
use Spryker\Zed\CustomerNoteGui\Dependency\Facade\CustomerNoteGuiToUserFacadeInterface;

class NoteWriter implements NoteWriterInterface
{
    protected const USERNAME_FORMAT = '%s %s';

    /**
     * @var \Spryker\Zed\CustomerNoteGui\Dependency\Facade\CustomerNoteGuiToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\CustomerNoteGui\Dependency\Facade\CustomerNoteGuiToCustomerNoteFacadeInterface
     */
    protected $customerNoteFacade;

    /**
     * @param \Spryker\Zed\CustomerNoteGui\Dependency\Facade\CustomerNoteGuiToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\CustomerNoteGui\Dependency\Facade\CustomerNoteGuiToCustomerNoteFacadeInterface $customerNoteFacade
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
    public function createCustomerNote(int $idCustomer, string $note): SpyCustomerNoteEntityTransfer
    {
        $commentTransfer = $this->createSpyCustomerNoteEntityTransfer($idCustomer, $note);

        return $this->customerNoteFacade->addNote($commentTransfer);
    }

    /**
     * @param int $idCustomer
     * @param string $note
     *
     * @return \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer
     */
    protected function createSpyCustomerNoteEntityTransfer(int $idCustomer, string $note): SpyCustomerNoteEntityTransfer
    {
        $commentTransfer = new SpyCustomerNoteEntityTransfer();
        $commentTransfer->setMessage($note);
        $commentTransfer->setFkCustomer($idCustomer);
        $currentUserTransfer = $this->userFacade->getCurrentUser();
        $commentTransfer->setUsername($this->formatCommenterUsername($currentUserTransfer));
        $commentTransfer->setFkUser($currentUserTransfer->getIdUser());

        return $commentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return string
     */
    protected function formatCommenterUsername(UserTransfer $userTransfer): string
    {
        return sprintf(
            static::USERNAME_FORMAT,
            $userTransfer->getFirstName(),
            $userTransfer->getLastName()
        );
    }
}
