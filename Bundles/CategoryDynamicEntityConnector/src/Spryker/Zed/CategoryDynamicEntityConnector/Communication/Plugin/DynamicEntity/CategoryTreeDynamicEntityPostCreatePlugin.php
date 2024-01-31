<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDynamicEntityConnector\Communication\Plugin\DynamicEntity;

use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;
use Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostCreatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CategoryDynamicEntityConnector\Business\CategoryDynamicEntityConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig getConfig()
 */
class CategoryTreeDynamicEntityPostCreatePlugin extends AbstractPlugin implements DynamicEntityPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `DynamicEntityPostEditRequest.tableName` to be set.
     * - Uses {@link \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig::getCategoryTreePublishOnCreateByDynamicEntityApplicableTables()} to get a list of applicable table names.
     * - Checks if `DynamicEntityPostEditRequest.tableName` is set to one of applicable table names.
     * - Triggers category tree publish event.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function postCreate(DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer): DynamicEntityPostEditResponseTransfer
    {
        return $this->getFacade()
            ->publishCategoryTreeOnCreateByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);
    }
}
