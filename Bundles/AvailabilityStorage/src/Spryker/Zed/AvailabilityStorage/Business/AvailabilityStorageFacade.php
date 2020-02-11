<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageBusinessFactory getFactory()
 */
class AvailabilityStorageFacade extends AbstractFacade implements AvailabilityStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $availabilityIds
     *
     * @return void
     */
    public function publish(array $availabilityIds)
    {
        $this->getFactory()->createAvailabilityStorage()->publish($availabilityIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $availabilityIds
     *
     * @return void
     */
    public function unpublish(array $availabilityIds)
    {
        $this->getFactory()->createAvailabilityStorage()->unpublish($availabilityIds);
    }
}
