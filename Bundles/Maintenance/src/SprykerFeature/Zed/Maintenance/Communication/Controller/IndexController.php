<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Maintenance\Business\MaintenanceFacade;

/**
 * @method MaintenanceFacade getFacade()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $installedPackages = $this->getFacade()->getInstalledPackages();

        return $this->viewResponse([
            'installedPackages' => $installedPackages,
        ]);
    }

}
