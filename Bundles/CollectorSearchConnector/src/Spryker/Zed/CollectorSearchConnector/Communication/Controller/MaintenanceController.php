<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CollectorSearchConnector\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Search\Communication\Controller\MaintenanceController as SearchMaintenanceController;

/**
 * @method \Spryker\Zed\CollectorSearchConnector\Communication\CollectorSearchConnectorCommunicationFactory getFactory()
 */
class MaintenanceController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function dropTimestampsAction()
    {
        $this->getFactory()->getCollectorFacade()->deleteSearchTimestamps();

        $this->addInfoMessage('Search collectors timestamps deleted');

        return $this->redirectResponse(SearchMaintenanceController::URL_SEARCH_MAINTENANCE);
    }
}
