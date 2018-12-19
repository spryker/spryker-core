<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestAbstractProductAvailabilityAttributesTransfer;
use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;

class AbstractProductAvailabilitiesResourceMapper implements AbstractProductAvailabilitiesResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer $availabilityEntityTransfer
     *
     * @return \Generated\Shared\Transfer\RestAbstractProductAvailabilityAttributesTransfer
     */
    public function mapAvailabilityTransferToRestAbstractProductAvailabilityAttributesTransfer(SpyAvailabilityAbstractEntityTransfer $availabilityEntityTransfer): RestAbstractProductAvailabilityAttributesTransfer
    {
        $restProductsAbstractAvailabilityAttributesTransfer = (new RestAbstractProductAvailabilityAttributesTransfer())
            ->fromArray($availabilityEntityTransfer->toArray(), true);
        $restProductsAbstractAvailabilityAttributesTransfer->setAvailability($this->isAbstractAvailable($availabilityEntityTransfer));

        return $restProductsAbstractAvailabilityAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer $availabilityEntityTransfer
     *
     * @return bool
     */
    protected function isAbstractAvailable(SpyAvailabilityAbstractEntityTransfer $availabilityEntityTransfer): bool
    {
        $isNeverOutOfStock = false;

        foreach ($availabilityEntityTransfer->getSpyAvailabilities() as $spyAvailabilityEntityTransfer) {
            $isNeverOutOfStock = $spyAvailabilityEntityTransfer->getIsNeverOutOfStock();
            if ($isNeverOutOfStock) {
                break;
            }
        }

        return $isNeverOutOfStock || $availabilityEntityTransfer->getQuantity() > 0;
    }
}
