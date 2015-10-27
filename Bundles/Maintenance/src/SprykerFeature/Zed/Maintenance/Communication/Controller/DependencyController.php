<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Maintenance\Business\MaintenanceFacade;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method MaintenanceFacade getFacade()
 */
class DependencyController extends AbstractController
{

    /**
     * @return array
     */
    public function outgoingAction(Request $request)
    {
        $bundleName = $request->query->get('bundle', 'Glossary');

        $dependencies = $this->getFacade()->showOutgoingDependenciesForBundle($bundleName);

        return $this->viewResponse([
            'bundle' => $bundleName,
            'dependencies' => $dependencies,
        ]);
    }

    public function incomingAction(Request $request)
    {
        $bundleName = $request->query->get('bundle', 'Glossary');

        $dependencies = $this->getFacade()->showIncomingDependenciesForBundle($bundleName);

        return $this->viewResponse([
            'bundle' => $bundleName,
            'dependencies' => $dependencies,
        ]);
    }

    public function graphAction(Request $request)
    {
        $bundleName = $request->query->get('bundle', 'Glossary');
        $response = $this->getFacade()->drawDependencyGraph($bundleName);

        $callback = function () use ($response) {
            echo $response;
        };

        return $this->streamedResponse($callback);
    }

}
