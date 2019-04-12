<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CollectorStorageConnector\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Storage\Communication\Controller\MaintenanceController as StorageMaintenanceController;

/**
 * @method \Spryker\Zed\CollectorStorageConnector\Communication\CollectorStorageConnectorCommunicationFactory getFactory()
 */
class MaintenanceController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function dropTimestampsAction()
    {
        $timestamps = $this->getFactory()->getStorageFacade()->getTimestamps();
        if (count($timestamps) === 0) {
            $this->addInfoMessage('No droppable timestamps found.');

            return $this->redirectResponse(StorageMaintenanceController::URL_STORAGE_MAINTENANCE);
        }

        $this->getFactory()->getCollectorFacade()->deleteStorageTimestamps($timestamps);

        $this->addSuccessMessage('Dropped "%d" timestamps.', ['%d' => count($timestamps)]);

        return $this->redirectResponse(StorageMaintenanceController::URL_STORAGE_MAINTENANCE);
    }
}
