<?php

namespace SprykerFeature\Zed\Maintenance\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class IndexController extends AbstractController
{

    public function indexAction()
    {
        $installedPackages = $this->getLocator()->maintenance()->facade()->getInstalledPackages();

        return $this->viewResponse([
            'installedPackages' => $installedPackages
        ]);
    }

}
