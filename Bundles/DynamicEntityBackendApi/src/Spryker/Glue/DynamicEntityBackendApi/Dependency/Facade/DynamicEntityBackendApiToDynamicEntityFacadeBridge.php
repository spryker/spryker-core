<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\DynamicEntityCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;

class DynamicEntityBackendApiToDynamicEntityFacadeBridge implements DynamicEntityBackendApiToDynamicEntityFacadeInterface
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
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionTransfer
     */
    public function getDynamicEntityCollection(
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
    ): DynamicEntityCollectionTransfer {
        return $this->dynamicEntityFacade->getDynamicEntityCollection($dynamicEntityCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function createDynamicEntityCollection(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityCollectionResponseTransfer {
        return $this->dynamicEntityFacade->createDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function updateDynamicEntityCollection(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityCollectionResponseTransfer {
        return $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionDeleteCriteriaTransfer $dynamicEntityCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function deleteDynamicEntityCollection(
        DynamicEntityCollectionDeleteCriteriaTransfer $dynamicEntityCollectionDeleteCriteriaTransfer
    ): DynamicEntityCollectionResponseTransfer {
        return $this->dynamicEntityFacade->deleteDynamicEntityCollection($dynamicEntityCollectionDeleteCriteriaTransfer);
    }
}
