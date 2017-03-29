<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Controller;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 */
class ArchitectureController extends AbstractController
{

    /**
     * vendor/spryker/spryker/Bundles/[BUNDLE]/src/
     * vendor/spryker/[BUNDLE]/src/
     *
     * @return array
     */
    public function indexAction()
    {
        $allBundles = $this->getFacade()->listAllBundles();

        return $this->viewResponse([
            'bundles' => $allBundles,
        ]);
    }

    public function checkBundleAction(Request $request)
    {
        $bundle = $request->query->get('bundle');
        $namespace = $request->query->get('namespace');
        $application = $request->query->get('application');
        $directory = $request->query->get('directory');

        $fileViolations = $this->getFacade()->runArchitectureSniffer($directory);

        return $this->viewResponse([
            'bundle' => $bundle,
            'namespace' => $namespace,
            'application' => $application,
            'fileViolations' => $fileViolations,
        ]);
    }



}
