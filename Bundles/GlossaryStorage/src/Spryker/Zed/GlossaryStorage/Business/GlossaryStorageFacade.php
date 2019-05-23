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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    public function unpublish(array $glossaryKeyIds)
    {
        $this->getFactory()->createGlossaryTranslationStorageWriter()->unpublish($glossaryKeyIds);
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
    public function deleteGlossaryStorageCollectionGlossaryKeyByGlossaryKeyEvents(array $eventTransfers)
    {
        $this->getFactory()->createGlossaryTranslationStorageWriter()->deleteGlossaryStorageCollectionByGlossaryKeyEvents($eventTransfers);
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
}
