<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Controller;

use Spryker\Zed\Development\Communication\Form\BundlesFormType;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacade getFacade()
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
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

        $bundleDependencyCollectionTransfer = $this->getFacade()->showOutgoingDependenciesForBundle($bundleName);
        $composerDependencies = $this->getFacade()->getComposerDependencyComparison($bundleDependencyCollectionTransfer);

        return $this->viewResponse([
            static::QUERY_KEY_BUNDLE => $bundleName,
            'dependencies' => $bundleDependencyCollectionTransfer,
            'composerDependencies' => $composerDependencies,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function outgoingGraphAction(Request $request)
    {
        $bundleName = $request->query->getAlnum(self::QUERY_KEY_BUNDLE);
        $dataProvider = $this->getFactory()->createBundleFormDataProvider($request, $bundleName);

        $form = $this->getFactory()
            ->createBundlesForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        $excludedBundles = [];
        $showIncoming = false;

        if ($form->isValid()) {
            $formData = $form->getData();
            if (isset($formData[BundlesFormType::EXCLUDED_BUNDLES])) {
                $excludedBundles = $formData[BundlesFormType::EXCLUDED_BUNDLES];
            }
            if (isset($formData[BundlesFormType::SHOW_INCOMING])) {
                $showIncoming = $formData[BundlesFormType::SHOW_INCOMING];
            }
        }

        $graph = $this->getFacade()->drawOutgoingDependencyTreeGraph($bundleName, $excludedBundles, $showIncoming);

        return $this->viewResponse([
            'form' => $form->createView(),
            'graph' => $graph,
        ]);
    }

    /**
     * @return array
     */
    public function stabilityAction()
    {
        $bundles = [];
        if (!file_exists(APPLICATION_ROOT_DIR . '/data/dependencyTree.json')) {
            $this->addInfoMessage('You need to run "vendor/bin/console dev:dependency:build-tree" to calculate stability for all bundles.');
        } else {
            $bundles = $this->getFacade()->calculateStability();
        }

        return $this->viewResponse([
            'bundles' => $bundles,
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

            if ($request->query->getBoolean(static::QUERY_KEY_BUILD_TREE, !$this->hasDependencyTreeCache())) {
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

            if ($request->query->getBoolean(static::QUERY_KEY_BUILD_TREE, !$this->hasDependencyTreeCache())) {
                $bundle = (is_string($bundleToView)) ? $bundleToView : '*';
                $this->getFacade()->buildDependencyTree('*', $bundle, '*');
            }

            echo $this->getFacade()->drawSimpleDependencyTreeGraph($showEngineBundle, $bundleToView);
        };

        return $this->streamedResponse($callback);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function adjacencyMatrixAction(Request $request)
    {
        if ($request->query->getBoolean(static::QUERY_KEY_BUILD_TREE, !$this->hasDependencyTreeCache())) {
            $this->getFacade()->buildDependencyTree('*', '*', '*');
        }

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

            if ($request->query->getBoolean(static::QUERY_KEY_BUILD_TREE, !$this->hasDependencyTreeCache())) {
                $bundle = (is_string($bundleToView)) ? $bundleToView : '*';
                $this->getFacade()->buildDependencyTree('*', $bundle, '*');
            }

            echo $this->getFacade()->drawExternalDependencyTreeGraph($bundleToView);
        };

        return $this->streamedResponse($callback);
    }

    /**
     * @return bool
     */
    protected function hasDependencyTreeCache()
    {
        return file_exists($this->getFactory()->getConfig()->getPathToJsonDependencyTree());
    }
}
