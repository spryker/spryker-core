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

interface DynamicEntityFacadeInterface
{
    /**
     * Specification:
     * - Retrieves a collection of entities based on the provided criteria.
     * - Returns an empty collection if the configuration for the requested entity is not found.
     * - Returns and filters data sets if they are defined in configuration for the requested entity.
     * - If `DynamicEntityCriteriaTransfer.relationChains` is present in the request and valid - returns data with relations.
     * - Returns errors if entity configuration is not valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionTransfer
     */
    public function getDynamicEntityCollection(DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer): DynamicEntityCollectionTransfer;

    /**
     * Specification:
     * - Saves a collection of entities based on the provided data.
     * - Filters passed data based on entity definition in `spy_dynamic_entity_configuration.definition`.
     * - Validates data types based on entity definition in `spy_dynamic_entity_configuration.definition`.
     * - Executes {@link \Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostCreatePluginInterface} plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function createDynamicEntityCollection(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityCollectionResponseTransfer;

    /**
     * Specification:
     * - Updates a collection of entities based on the provided data.
     * - Filters passed data based on entity definition in `spy_dynamic_entity_configuration.definition`.
     * - Validates data types based on entity definition in `spy_dynamic_entity_configuration.definition`.
     * - Executes {@link \Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostUpdatePluginInterface} plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function updateDynamicEntityCollection(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityCollectionResponseTransfer;

    /**
     * Specification:
     * - Retrieves a collection of entity configurations based on the specified criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    public function getDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
    ): DynamicEntityConfigurationCollectionTransfer;

    /**
     * Specification:
     *  - Installs Dynamic Entity data.
     *
     * @api
     *
     * @return void
     */
    public function install(): void;

    /**
     * Specification:
     * - Returns a list of tables that should not be used for dynamic entity configuration.
     *
     * @api
     *
     * @return array<string>
     */
    public function getDisallowedTables(): array;

    /**
     * Specification:
     * - Creates a collection of entity configurations based on the provided data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    public function createDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer;

    /**
     * Specification:
     * - Updates a collection of entity configurations based on the provided data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    public function updateDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer;

    /**
     * Specification:
     * - Deletes a collection of entities based on the provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionDeleteCriteriaTransfer $dynamicEntityCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function deleteDynamicEntityCollection(
        DynamicEntityCollectionDeleteCriteriaTransfer $dynamicEntityCollectionDeleteCriteriaTransfer
    ): DynamicEntityCollectionResponseTransfer;
}
