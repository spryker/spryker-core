<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\GlossaryStorage\Business\GlossaryStorageBusinessFactory getFactory()
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
        $this->getFactory()->createGlossaryTranslationStorageWriter()->writeGlossaryStorageCollection($glossaryKeyIds);
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
        $this->getFactory()->createGlossaryTranslationStorageWriter()->deleteGlossaryDeleteCollection($glossaryKeyIds);
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
    public function writeGlossaryStorageCollection(array $glossaryKeyIds)
    {
        $this->getFactory()->createGlossaryTranslationStorageWriter()->writeGlossaryStorageCollection($glossaryKeyIds);
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
    public function deleteGlossaryStorageCollection(array $glossaryKeyIds)
    {
        $this->getFactory()->createGlossaryTranslationStorageWriter()->deleteGlossaryDeleteCollection($glossaryKeyIds);
    }
}
