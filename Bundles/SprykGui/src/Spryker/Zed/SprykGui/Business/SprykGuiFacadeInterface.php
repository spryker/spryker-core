<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business;

use Generated\Shared\Transfer\AccessibleTransferCollection;
use Generated\Shared\Transfer\ClassInformationTransfer;
use Generated\Shared\Transfer\ModuleCollectionTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationCollectionTransfer;

interface SprykGuiFacadeInterface
{
    /**
     * Specification:
     * - Builds the template for JIRA.
     *
     * @api
     *
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return array
     */
    public function buildSprykView(string $sprykName, array $sprykArguments): array;

    /**
     * Specification:
     * - Returns all SprykDefinitions.
     *
     * @api
     *
     * @return array
     */
    public function getSprykDefinitions(): array;

    /**
     * Specification:
     * - Renders a rph for the given Spryk name.
     *
     * @api
     *
     * @param string $sprykName
     *
     * @return string
     */
    public function drawSpryk(string $sprykName): string;

    /**
     * Specification:
     * - Builds the commandLin to be executed and executes it.
     *
     * @api
     *
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return string
     */
    public function runSpryk(string $sprykName, array $sprykArguments): string;

    /**
     * Specification
     * - Returns a list with TransferObjects.
     * - Each TransferObject contains information about a found module.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ModuleCollectionTransfer
     */
    public function getModules(): ModuleCollectionTransfer;

    /**
     * Specification
     * - Returns a list with TransferObjects.
     * - Each TransferObject contains information about a organization.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\OrganizationCollectionTransfer
     */
    public function getOrganizations(): OrganizationCollectionTransfer;

    /**
     * Specification
     * - Returns a list with all TransferObjects which are accessible by a given module.
     *
     * @api
     *
     * @param string $module
     *
     * @return \Generated\Shared\Transfer\AccessibleTransferCollection
     */
    public function getAccessibleTransfers(string $module): AccessibleTransferCollection;

    /**
     * Specification
     * - Returns a list with all methods and their return type.
     *
     * @api
     *
     * @param string $className
     *
     * @return \Generated\Shared\Transfer\ClassInformationTransfer
     */
    public function getFactoryInformation(string $className): ClassInformationTransfer;

    /**
     * Specification:
     * - Returns an array with the Spryk definition.
     *
     * @api
     *
     * @param string $spryk
     *
     * @return array
     */
    public function getSprykDefinitionByName(string $spryk): array;

    /**
     * Specification:
     * - Loads all possible options for a Spryk.
     * - Adds OptionsTransfer to ModuleTransfer.
     * - Returns ModuleTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    public function buildOptions(ModuleTransfer $moduleTransfer): ModuleTransfer;

    /**
     * Specification:
     * - Finds attached choiceLoader by passed choiceLoader name.
     * - Loads elements for a choice field type with found ChoiceLoaderInterface.
     *
     * @api
     *
     * @param string $choiceLoaderName
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function loadChoices(string $choiceLoaderName, ModuleTransfer $moduleTransfer): array;
}
