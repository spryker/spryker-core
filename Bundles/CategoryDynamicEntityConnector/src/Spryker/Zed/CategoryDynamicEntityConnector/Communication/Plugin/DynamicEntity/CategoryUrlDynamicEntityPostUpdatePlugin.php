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
 * @method \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig getConfig()
 * @method \Spryker\Zed\CategoryDynamicEntityConnector\Business\CategoryDynamicEntityConnectorFacadeInterface getFacade()
 */
class CategoryUrlDynamicEntityPostUpdatePlugin extends AbstractPlugin implements DynamicEntityPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `DynamicEntityPostEditRequest.tableName` to be set.
     * - Uses {@link \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig::getCategoryUrlOnUpdateByDynamicEntityApplicableTables()} to get a list of applicable table names.
     * - Checks if `DynamicEntityPostEditRequest.tableName` is set to one of applicable table names.
     * - Expects `fk_category` field to be provided in `DynamicEntityPostEditRequestTransfer.rawDynamicEntities.fields`.
     * - Creates category URLs by provided dynamic entity data.
     * - Category should have at least one category node and category attribute.
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
            ->updateCategoryUrlByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);
    }
}
