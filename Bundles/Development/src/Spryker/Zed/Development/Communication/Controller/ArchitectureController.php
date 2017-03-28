<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 */
class ArchitectureController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $bundles = $this->getFacade()->getAllBundles();

        return $this->viewResponse([
            'bundles' => $bundles,
        ]);
    }

    public function checkBundleAction(Request $request)
    {
        $bundle = $request->query->getAlpha('bundle');

        $fileViolations = $this->getFacade()->runArchitectureSniffer($bundle);

        return $this->viewResponse([
            'bundle' => $bundle,
            'fileViolations' => $fileViolations,
        ]);
    }

}
