<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\GlossaryStorage\Business\GlossaryStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageRepositoryInterface getRepository()
 */
class GlossaryStorageFacade extends AbstractFacade implements GlossaryStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    public function publish(array $glossaryKeyIds)
    {
        $this->getFactory()->createGlossaryTranslationStorageWriter()->publish($glossaryKeyIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    public function unpublish(array $glossaryKeyIds)
    {
        $this->getFactory()->createGlossaryTranslationStorageDeleter()->unpublish($glossaryKeyIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByGlossaryKeyEvents(array $eventTransfers)
    {
        $this->getFactory()->createGlossaryTranslationStorageWriter()->writeGlossaryStorageCollectionByGlossaryKeyEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByGlossaryKeyEvents(array $eventTransfers): void
    {
        $this->getFactory()->createGlossaryTranslationStorageDeleter()->deleteGlossaryStorageCollectionByGlossaryKeyEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByGlossaryTranslationEvents(array $eventTransfers): void
    {
        $this->getFactory()->createGlossaryTranslationStorageWriter()->writeGlossaryStorageCollectionByGlossaryTranslationEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\GlossaryKeyTransfer[]
     */
    public function findFilteredGlossaryKeyEntities(int $offset, int $limit): array
    {
        return $this->getRepository()->findFilteredGlossaryKeyEntities($offset, $limit);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findGlossaryStorageDataTransferByIds(int $offset, int $limit, array $ids): array
    {
        return $this->getRepository()->findGlossaryStorageDataTransferByIds($offset, $limit, $ids);
    }
}
