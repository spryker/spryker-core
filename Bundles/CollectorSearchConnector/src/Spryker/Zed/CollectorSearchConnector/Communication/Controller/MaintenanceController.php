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
     * @var string
     */
    protected const MESSAGE_ERROR_COLLECTORS_NOT_SUPPORTED_IN_DMS_ON_MODE = 'Collectors are not supported in Dynamic Multistore mode.';

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function dropTimestampsAction()
    {
        /* Required by infrastructure, exists only for BC with DMS OFF mode. */
        if ($this->getFactory()->getStoreFacade()->isDynamicStoreEnabled()) {
            $this->addErrorMessage(static::MESSAGE_ERROR_COLLECTORS_NOT_SUPPORTED_IN_DMS_ON_MODE);

            return $this->redirectResponse(SearchMaintenanceController::URL_SEARCH_MAINTENANCE);
        }

        $this->getFactory()->getCollectorFacade()->deleteSearchTimestamps();

        $this->addInfoMessage('Search collectors timestamps deleted');

        return $this->redirectResponse(SearchMaintenanceController::URL_SEARCH_MAINTENANCE);
    }
}
