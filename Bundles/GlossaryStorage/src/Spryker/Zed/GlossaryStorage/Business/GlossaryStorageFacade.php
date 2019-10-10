<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $eventTransfers
     *
     * @return void
     */
    public function writeGlossaryStorageCollectionByGlossaryKeyEvents(array $eventTransfers)
    {
        $this->getFactory()->createGlossaryTranslationStorageWriter()->writeGlossaryStorageCollectionByGlossaryKeyEvents($eventTransfers);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $eventTransfers
     *
     * @return void
     */
    public function deleteGlossaryStorageCollectionByGlossaryKeyEvents(array $eventTransfers)
    {
        $this->getFactory()->createGlossaryTranslationStorageDeleter()->deleteGlossaryStorageCollectionByGlossaryKeyEvents($eventTransfers);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $eventTransfers
     *
     * @return void
     */
    public function writeGlossaryStorageCollectionByGlossaryTranslationEvents(array $eventTransfers)
    {
        $this->getFactory()->createGlossaryTranslationStorageWriter()->writeGlossaryStorageCollectionByGlossaryTranslationEvents($eventTransfers);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryKeyEntityTransfer[]
     */
    public function findFilteredGlossaryKeyEntityTransfers(FilterTransfer $filterTransfer)
    {
        return $this->getRepository()->findFilteredGlossaryKeyEntityTransfers($filterTransfer);
    }
}
