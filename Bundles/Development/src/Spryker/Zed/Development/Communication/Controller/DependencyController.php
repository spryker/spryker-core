<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacade getFacade()
 */
class DependencyController extends AbstractController
{

    const APPLICATION_ZED = 'Zed';
    const QUERY_KEY_BUILD_TREE = 'build-tree';
    const QUERY_KEY_BUNDLE = 'bundle';

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
        $bundleName = $request->query->getAlnum(static::QUERY_KEY_BUNDLE);

        $dependencies = $this->getFacade()->showOutgoingDependenciesForBundle($bundleName);

        $composerDependencies = $this->getFacade()->getComposerDependencyComparison($bundleName, array_keys($dependencies));

        return $this->viewResponse([
            static::QUERY_KEY_BUNDLE => $bundleName,
            'dependencies' => $dependencies,
            'composerDependencies' => $composerDependencies,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function incomingAction(Request $request)
    {
        $bundleName = $request->query->getAlnum(static::QUERY_KEY_BUNDLE);

        $dependencies = $this->getFacade()->showIncomingDependenciesForBundle($bundleName);

        return $this->viewResponse([
            static::QUERY_KEY_BUNDLE => $bundleName,
            'dependencies' => $dependencies,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function dependencyTreeGraphAction(Request $request)
    {
        if (!$request->query->has(static::QUERY_KEY_BUNDLE)) {
            $this->addErrorMessage('You must specify a bundle for which the graph should be build');

            return $this->redirectResponse('/development/dependency');
        }

        $callback = function () use ($request) {
            $bundleToView = $request->query->get(static::QUERY_KEY_BUNDLE, false);

            if ($request->query->getBoolean(static::QUERY_KEY_BUILD_TREE, true)) {
                $this->getFacade()->buildDependencyTree('*', $bundleToView, '*');
            }

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
            $bundleToView = $request->query->getBoolean(static::QUERY_KEY_BUNDLE, false);
            $showEngineBundle = $request->query->getBoolean('show-engine-bundle', true);

            if ($request->query->getBoolean(static::QUERY_KEY_BUILD_TREE, true)) {
                $bundle = (is_string($bundleToView)) ? $bundleToView : '*';
                $this->getFacade()->buildDependencyTree('*', $bundle, '*');
            }

            echo $this->getFacade()->drawSimpleDependencyTreeGraph($showEngineBundle, $bundleToView);
        };

        return $this->streamedResponse($callback);
    }

    /**
     * @return array
     */
    public function adjacencyMatrixAction()
    {
        $this->getFacade()->buildDependencyTree('*', '*', '*');

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
            $bundleToView = $request->query->getBoolean(static::QUERY_KEY_BUNDLE, false);

            if ($request->query->getBoolean(static::QUERY_KEY_BUILD_TREE, true)) {
                $bundle = (is_string($bundleToView)) ? $bundleToView : '*';
                $this->getFacade()->buildDependencyTree('*', $bundle, '*');
            }

            echo $this->getFacade()->drawExternalDependencyTreeGraph($bundleToView);
        };

        return $this->streamedResponse($callback);
    }

}
