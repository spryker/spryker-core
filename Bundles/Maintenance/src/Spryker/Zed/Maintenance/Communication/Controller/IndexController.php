<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Maintenance\Business\MaintenanceFacade;
use Spryker\Zed\Maintenance\Communication\MaintenanceDependencyContainer;

/**
 * @method MaintenanceFacade getFacade()
 * @method MaintenanceDependencyContainer getCommunicationFactory()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->viewResponse([
        ]);
    }

    /**
     * @return array
     */
    public function packagesAction()
    {
        $installedPackages = $this->getFacade()->getInstalledPackages();

        return $this->viewResponse([
            'installedPackages' => $installedPackages,
        ]);
    }

}
