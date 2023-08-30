<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Dependency\Facade;

use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;

interface DynamicEntityGuiToDynamicEntityFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    public function getDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
    ): DynamicEntityConfigurationCollectionTransfer;

    /**
     * @return array<string>
     */
    public function getDisallowedTables(): array;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    public function updateDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    public function createDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer;
}
