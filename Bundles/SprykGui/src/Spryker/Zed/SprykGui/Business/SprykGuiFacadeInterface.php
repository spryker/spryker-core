<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business;

use Generated\Shared\Transfer\AccessibleTransferCollection;
use Generated\Shared\Transfer\ClassInformationTransfer;
use Generated\Shared\Transfer\ModuleCollectionTransfer;

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
    public function getModuleDetails(): ModuleCollectionTransfer;

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
}
