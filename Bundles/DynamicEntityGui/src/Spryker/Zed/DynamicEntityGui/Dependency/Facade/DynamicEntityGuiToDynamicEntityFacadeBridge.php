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

class DynamicEntityGuiToDynamicEntityFacadeBridge implements DynamicEntityGuiToDynamicEntityFacadeInterface
{
    /**
     * @var \Spryker\Zed\DynamicEntity\Business\DynamicEntityFacadeInterface
     */
    protected $dynamicEntityFacade;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\DynamicEntityFacadeInterface $dynamicEntityFacade
     */
    public function __construct($dynamicEntityFacade)
    {
        $this->dynamicEntityFacade = $dynamicEntityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    public function getDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
    ): DynamicEntityConfigurationCollectionTransfer {
        return $this->dynamicEntityFacade->getDynamicEntityConfigurationCollection($dynamicEntityConfigurationCriteriaTransfer);
    }

    /**
     * @return array<string>
     */
    public function getDisallowedTables(): array
    {
        return $this->dynamicEntityFacade->getDisallowedTables();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    public function updateDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer {
        return $this->dynamicEntityFacade->updateDynamicEntityConfigurationCollection($dynamicEntityConfigurationCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    public function createDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer {
        return $this->dynamicEntityFacade->createDynamicEntityConfigurationCollection($dynamicEntityConfigurationCollectionTransfer);
    }
}
