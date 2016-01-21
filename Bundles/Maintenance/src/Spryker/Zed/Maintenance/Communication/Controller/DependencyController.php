<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Maintenance\Business\MaintenanceFacade;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method MaintenanceFacade getFacade()
 */
class DependencyController extends AbstractController
{

    const QUERY_BUNDLE = 'bundle';

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

    /**
     * @param Request $request
     *
     * @return array
     */
    public function outgoingAction(Request $request)
    {
        $bundleName = $request->query->get(self::QUERY_BUNDLE);

        $dependencies = $this->getFacade()->showOutgoingDependenciesForBundle($bundleName);

        return $this->viewResponse([
            self::QUERY_BUNDLE => $bundleName,
            'dependencies' => $dependencies,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function incomingAction(Request $request)
    {
        $bundleName = $request->query->get(self::QUERY_BUNDLE);

        $dependencies = $this->getFacade()->showIncomingDependenciesForBundle($bundleName);

        return $this->viewResponse([
            self::QUERY_BUNDLE => $bundleName,
            'dependencies' => $dependencies,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return StreamedResponse
     */
    public function graphAction(Request $request)
    {
        $bundleName = $request->query->get(self::QUERY_BUNDLE);
        $response = $this->getFacade()->drawDependencyGraph($bundleName);

        $callback = function () use ($response) {
            echo $response;
        };

        return $this->streamedResponse($callback);
    }

    /**
     * @return StreamedResponse
     */
    public function dependencyTreeGraphAction()
    {
        $callback = function() {
            $this->getFacade()->drawDependencyTreeGraph();
        };

        return $this->streamedResponse($callback);
    }

}
