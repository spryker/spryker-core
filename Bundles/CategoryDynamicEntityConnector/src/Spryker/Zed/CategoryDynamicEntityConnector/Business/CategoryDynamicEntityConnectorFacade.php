<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDynamicEntityConnector\Business;

use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CategoryDynamicEntityConnector\Business\CategoryDynamicEntityConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\CategoryDynamicEntityConnector\Persistence\CategoryDynamicEntityConnectorRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryDynamicEntityConnector\Persistence\CategoryDynamicEntityConnectorEntityManagerInterface getEntityManager()
 */
class CategoryDynamicEntityConnectorFacade extends AbstractFacade implements CategoryDynamicEntityConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function createCategoryUrlByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        return $this->getFactory()
            ->createCategoryUrlCreator()
            ->createCategoryUrlByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function updateCategoryUrlByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        return $this->getFactory()
            ->createCategoryUrlUpdater()
            ->updateCategoryUrlByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function createCategoryClosureTableByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        return $this->getFactory()
            ->createCategoryClosureTableCreator()
            ->createCategoryClosureTableByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function updateCategoryClosureTableByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        return $this->getFactory()
            ->createCategoryClosureTableUpdater()
            ->updateCategoryClosureTableByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function publishCategoryTreeOnCreateByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        return $this->getFactory()
            ->createCategoryTreePublisher()
            ->publishCategoryTreeOnCreateByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function publishCategoryTreeOnUpdateByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        return $this->getFactory()
            ->createCategoryTreePublisher()
            ->publishCategoryTreeOnUpdateByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);
    }
}
