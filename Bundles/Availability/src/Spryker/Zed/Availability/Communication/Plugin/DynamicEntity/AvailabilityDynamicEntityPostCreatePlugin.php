<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Communication\Plugin\DynamicEntity;

use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;
use Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostCreatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface getFacade()
 * @method \Spryker\Zed\Availability\Communication\AvailabilityCommunicationFactory getFactory()
 * @method \Spryker\Zed\Availability\AvailabilityConfig getConfig()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface getQueryContainer()
 */
class AvailabilityDynamicEntityPostCreatePlugin extends AbstractPlugin implements DynamicEntityPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `DynamicEntityPostEditRequest.tableName` to be set.
     * - Checks if `DynamicEntityPostEditRequest.tableName` is set to `spy_stock_product`.
     * - Requires `RawDynamicEntity.fields` to be set for each element in `DynamicEntityPostEditRequest.rawDynamicEntities`.
     * - Checks if `spy_stock_product` is updated and updates availability.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function postCreate(DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer): DynamicEntityPostEditResponseTransfer
    {
        return $this->getFacade()->updateAvailabilityByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);
    }
}
