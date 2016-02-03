<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Maintenance\Business\MaintenanceFacade getFacade()
 * @method \Spryker\Zed\Maintenance\Communication\MaintenanceCommunicationFactory getFactory()
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
