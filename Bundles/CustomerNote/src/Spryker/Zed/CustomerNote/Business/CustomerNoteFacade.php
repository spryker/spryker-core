<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Business;

use Generated\Shared\Transfer\CustomerNoteCollectionTransfer;
use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerNote\Persistence\CustomerNoteEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomerNote\Persistence\CustomerNoteRepositoryInterface getRepository()
 * @method \Spryker\Zed\CustomerNote\Business\CustomerNoteBusinessFactory getFactory()
 */
class CustomerNoteFacade extends AbstractFacade implements CustomerNoteFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer $customerNoteEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer
     */
    public function addNote(SpyCustomerNoteEntityTransfer $customerNoteEntityTransfer): SpyCustomerNoteEntityTransfer
    {
        return $this->getEntityManager()->saveNote($customerNoteEntityTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer $customerNoteEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer
     */
    public function addNoteFromCurrentUser(SpyCustomerNoteEntityTransfer $customerNoteEntityTransfer): SpyCustomerNoteEntityTransfer
    {
        return $this->getFactory()->createNoteWriter()->createCustomerNote($customerNoteEntityTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerNoteCollectionTransfer
     */
    public function getNotes(int $idCustomer): CustomerNoteCollectionTransfer
    {
        return $this->getRepository()->getCustomerNoteCollectionByIdCustomer($idCustomer);
    }
}
