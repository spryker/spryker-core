<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Maintenance\Business\MaintenanceBusinessFactory getFactory()
 */
class MaintenanceFacade extends AbstractFacade implements MaintenanceFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function enableMaintenanceForZed()
    {
        $this->getFactory()->createMaintenanceForZed()->enableMaintenance();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function disableMaintenanceForZed()
    {
        $this->getFactory()->createMaintenanceForZed()->disableMaintenance();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function enableMaintenanceForYves()
    {
        $this->getFactory()->createMaintenanceForYves()->enableMaintenance();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function disableMaintenanceForYves()
    {
        $this->getFactory()->createMaintenanceForYves()->disableMaintenance();
    }
}
