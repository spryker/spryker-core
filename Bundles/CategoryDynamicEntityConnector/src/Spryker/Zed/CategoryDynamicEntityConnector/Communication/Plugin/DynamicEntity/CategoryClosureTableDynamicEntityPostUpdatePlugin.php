<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDynamicEntityConnector\Communication\Plugin\DynamicEntity;

use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;
use Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostUpdatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CategoryDynamicEntityConnector\Business\CategoryDynamicEntityConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig getConfig()
 */
class CategoryClosureTableDynamicEntityPostUpdatePlugin extends AbstractPlugin implements DynamicEntityPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Should be executed before {@link \Spryker\Zed\CategoryDynamicEntityConnector\Communication\Plugin\DynamicEntity\CategoryUrlDynamicEntityPostUpdatePlugin}.
     * - Requires `DynamicEntityPostEditRequest.tableName` to be set.
     * - Uses {@link \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig::getCategoryClosureTableOnUpdateByDynamicEntityApplicableTables()} to get a list of applicable table names.
     * - Checks if `DynamicEntityPostEditRequest.tableName` is set to one of applicable table names.
     * - Does nothing if DynamicEntityPostEditRequestTransfer.rawDynamicEntities.fields['id_category_node'] is not provided.
     * - Does nothing if DynamicEntityPostEditRequestTransfer.rawDynamicEntities.fields['fk_parent_category_node'] is not provided.
     * - Updates category closure table entities for provided category node if `fk_parent_category_node` is changed.
     * - Persists category closure table entity by provided dynamic entity data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function postUpdate(DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer): DynamicEntityPostEditResponseTransfer
    {
        return $this->getFacade()
            ->updateCategoryClosureTableByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);
    }
}
