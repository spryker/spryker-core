<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business;

use Generated\Shared\Transfer\DynamicEntityCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DynamicEntity\Business\DynamicEntityBusinessFactory getFactory()
 * @method \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface getRepository()
 * @method \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface getEntityManager()
 */
class DynamicEntityFacade extends AbstractFacade implements DynamicEntityFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionTransfer
     */
    public function getDynamicEntityCollection(DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer): DynamicEntityCollectionTransfer
    {
        return $this->getFactory()->createDynamicEntityReader()->getEntityCollection($dynamicEntityCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function createDynamicEntityCollection(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityCollectionResponseTransfer {
        return $this->getFactory()->createDynamicEntityCreator()->create($dynamicEntityCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function updateDynamicEntityCollection(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityCollectionResponseTransfer {
        return $this->getFactory()->createDynamicEntityUpdater()->update($dynamicEntityCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    public function getDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
    ): DynamicEntityConfigurationCollectionTransfer {
        return $this->getRepository()->getDynamicEntityConfigurationCollection($dynamicEntityConfigurationCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function install(): void
    {
        $this->getFactory()
            ->createDynamicEntityInstaller()
            ->install();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getDisallowedTables(): array
    {
        return $this->getFactory()->createDisallowedTablesReader()->getDisallowedTables();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    public function createDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer {
        return $this->getFactory()->createDynamicEntityConfigurationCreator()->createDynamicEntityConfigurationCollection($dynamicEntityConfigurationCollectionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    public function updateDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer {
        return $this->getFactory()->createDynamicEntityConfigurationUpdater()->updateDynamicEntityConfigurationCollection($dynamicEntityConfigurationCollectionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionDeleteCriteriaTransfer $dynamicEntityCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function deleteDynamicEntityCollection(
        DynamicEntityCollectionDeleteCriteriaTransfer $dynamicEntityCollectionDeleteCriteriaTransfer
    ): DynamicEntityCollectionResponseTransfer {
        return $this->getFactory()->createDynamicEntityDeleter()->deleteEntityCollection($dynamicEntityCollectionDeleteCriteriaTransfer);
    }
}
