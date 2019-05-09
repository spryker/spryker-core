<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CustomerNoteCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CustomerNote\Persistence\CustomerNotePersistenceFactory getFactory()
 */
class CustomerNoteRepository extends AbstractRepository implements CustomerNoteRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerNoteCollectionTransfer
     */
    public function getCustomerNoteCollectionByIdCustomer(int $idCustomer): CustomerNoteCollectionTransfer
    {
        $customerNoteQuery = $this->getFactory()->createCustomerNoteQuery();
        $customerNoteQuery->filterByFkCustomer($idCustomer);
        $customerNoteEntityTransfers = $this->buildQueryFromCriteria($customerNoteQuery)->find();

        return $this->prepareCustomerNoteCollectionTransfer($customerNoteEntityTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer[] $customerNoteEntityTransfers
     *
     * @return \Generated\Shared\Transfer\CustomerNoteCollectionTransfer
     */
    protected function prepareCustomerNoteCollectionTransfer(array $customerNoteEntityTransfers): CustomerNoteCollectionTransfer
    {
        $notesCollection = new ArrayObject($customerNoteEntityTransfers);
        $collectionTransfer = new CustomerNoteCollectionTransfer();
        $collectionTransfer->setNotes($notesCollection);

        return $collectionTransfer;
    }
}
