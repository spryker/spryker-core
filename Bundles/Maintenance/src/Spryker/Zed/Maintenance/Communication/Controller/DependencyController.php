<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \Spryker\Zed\Maintenance\Business\MaintenanceFacade getFacade()
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
     * @param \Symfony\Component\HttpFoundation\Request $request
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
     * @param \Symfony\Component\HttpFoundation\Request $request
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function dependencyTreeGraphAction(Request $request)
    {
        if (!$request->query->get('bundle', false)) {
            $this->addErrorMessage('You must specify a bundle for which the graph should be build');

            return $this->redirectResponse('/maintenance/dependency');
        }

        $callback = function () use ($request) {
            $bundleToView = $request->query->get('bundle', false);
            echo $this->getFacade()->drawDetailedDependencyTreeGraph($bundleToView);
        };

        return $this->streamedResponse($callback);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function simpleAction(Request $request)
    {
        $callback = function () use ($request) {
            $bundleToView = $request->query->get('bundle', false);
            echo $this->getFacade()->drawSimpleDependencyTreeGraph($bundleToView);
        };

        return $this->streamedResponse($callback);
    }

    /**
     * @return array
     */
    public function adjacencyMatrixAction()
    {
        $matrixData = $this->getFacade()->getAdjacencyMatrixData();
        $engineBundleList = $this->getFacade()->getEngineBundleList();

        return $this->viewResponse(['matrixData' => $matrixData, 'engineBundles' => $engineBundleList]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function externalDependencyTreeAction(Request $request)
    {
        $callback = function () use ($request) {
            $bundleToView = $request->query->get('bundle', false);
            echo $this->getFacade()->drawExternalDependencyTreeGraph($bundleToView);
        };

        return $this->streamedResponse($callback);
    }

}
