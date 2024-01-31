<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDynamicEntityConnector\Business;

use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;

interface CategoryDynamicEntityConnectorFacadeInterface
{
    /**
     * Specification:
     * - Requires `DynamicEntityPostEditRequest.tableName` to be set.
     * - Uses {@link \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig::getCategoryUrlOnCreateByDynamicEntityApplicableTables()} to get a list of applicable table names.
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
    public function createCategoryUrlByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer;

    /**
     * Specification:
     * - Requires `DynamicEntityPostEditRequest.tableName` to be set.
     * - Uses {@link \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig::getCategoryClosureTableOnCreateByDynamicEntityApplicableTables()} to get a list of applicable table names.
     * - Checks if `DynamicEntityPostEditRequest.tableName` is set to one of applicable table names.
     * - Persists category closure table entity by provided dynamic entity data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function createCategoryClosureTableByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer;

    /**
     * Specification:
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
    public function publishCategoryTreeOnCreateByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer;

    /**
     * Specification:
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
    public function updateCategoryUrlByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer;

    /**
     * Specification:
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
    public function updateCategoryClosureTableByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer;

    /**
     * Specification:
     * - Requires `DynamicEntityPostEditRequest.tableName` to be set.
     * - Uses {@link \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig::getCategoryTreePublishOnUpdateByDynamicEntityApplicableTables()} to get a list of applicable table names.
     * - Checks if `DynamicEntityPostEditRequest.tableName` is set to one of applicable table names.
     * - Triggers category tree publish event.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function publishCategoryTreeOnUpdateByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer;
}
