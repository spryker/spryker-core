<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CustomerNotesCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CustomerNote\Persistence\CustomerNotePersistenceFactory getFactory()
 */
class CustomerNoteRepository extends AbstractRepository implements CustomerNoteRepositoryInterface
{
    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerNotesCollectionTransfer
     */
    public function findCustomerCommentCollectionByCustomerId(int $idCustomer): CustomerNotesCollectionTransfer
    {
        $customerNoteQuery = $this->getFactory()->createCustomerNoteQuery();
        $customerNoteQuery->filterByFkCustomer($idCustomer);
        $customerNotes = $this->buildQueryFromCriteria($customerNoteQuery)->find();
        $notesCollection = new ArrayObject($customerNotes);
        $collectionTransfer = new CustomerNotesCollectionTransfer();
        $collectionTransfer->setNotes($notesCollection);

        return $collectionTransfer;
    }
}
