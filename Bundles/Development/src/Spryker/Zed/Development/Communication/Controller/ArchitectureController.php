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
     * vendor/spryker/spryker/Bundles/[BUNDLE]/src/
     * vendor/spryker/[BUNDLE]/src/
     *
     * @return array
     */
    public function indexAction()
    {
        $allModules = $this->getFacade()->listAllModules();

        return $this->viewResponse([
            'bundles' => $allModules,
        ]);
    }

    /**
     * @return array
     */
    public function rulesAction()
    {
        $rules = $this->getFacade()->getArchitectureRules();

        $groupedRules = [];
        foreach ($rules as $rule) {
            $groupedRules[$rule['ruleset']][] = $rule;
        }
        foreach ($groupedRules as &$collection) {
            ksort($collection);
        }
        unset($collection);

        ksort($groupedRules);

        return $this->viewResponse([
            'groupedRules' => $groupedRules,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
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
