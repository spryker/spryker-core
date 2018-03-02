<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Business;

use Generated\Shared\Transfer\CustomerNotesCollectionTransfer;
use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerNote\Persistence\CustomerNoteEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomerNote\Persistence\CustomerNoteRepositoryInterface getRepository()
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
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerNotesCollectionTransfer
     */
    public function getNotes(int $idCustomer): CustomerNotesCollectionTransfer
    {
        return $this->getRepository()->getCustomerCommentCollectionByIdCustomer($idCustomer);
    }
}
