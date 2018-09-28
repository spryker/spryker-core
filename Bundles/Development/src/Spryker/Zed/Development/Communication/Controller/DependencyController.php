<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Controller;

use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Spryker\Zed\Development\Communication\Form\BundlesFormType;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 */
class DependencyController extends AbstractController
{
    public const APPLICATION_ZED = 'Zed';
    public const QUERY_KEY_BUILD_TREE = 'build-tree';
    public const QUERY_KEY_MODULE = 'bundle';

    /**
     * @return array
     */
    public function indexAction()
    {
        $bundles = $this->getFacade()->getAllModules();

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
        $moduleName = $request->query->getAlnum(static::QUERY_KEY_MODULE);

        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer->setName('Spryker');
        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer
            ->setName($moduleName)
            ->setOrganization($organizationTransfer);

        $bundleDependencyCollectionTransfer = $this->getFacade()->showOutgoingDependenciesForModule($moduleTransfer);
        $composerDependencies = $this->getFacade()->getComposerDependencyComparison($bundleDependencyCollectionTransfer);

        return $this->viewResponse([
            static::QUERY_KEY_MODULE => $moduleName,
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
        $moduleName = $request->query->getAlnum(self::QUERY_KEY_MODULE);

        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer->setName('Spryker');
        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer
            ->setName($moduleName)
            ->setOrganization($organizationTransfer);

        $dataProvider = $this->getFactory()->createBundleFormDataProvider($request, $moduleTransfer);

        $form = $this->getFactory()
            ->createBundlesForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        $excludedBundles = [];
        $showIncoming = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            if (isset($formData[BundlesFormType::EXCLUDED_BUNDLES])) {
                $excludedBundles = $formData[BundlesFormType::EXCLUDED_BUNDLES];
            }
            if (isset($formData[BundlesFormType::SHOW_INCOMING])) {
                $showIncoming = $formData[BundlesFormType::SHOW_INCOMING];
            }
        }

        $graph = $this->getFacade()->drawOutgoingDependencyTreeGraph($moduleName, $excludedBundles, $showIncoming);

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
        $module = $request->query->getAlnum(static::QUERY_KEY_MODULE);

        $dependencies = $this->getFacade()->showIncomingDependenciesForModule($module);

        return $this->viewResponse([
            static::QUERY_KEY_MODULE => $module,
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
        if (!$request->query->has(static::QUERY_KEY_MODULE)) {
            $this->addErrorMessage('You must specify a bundle for which the graph should be build');

            return $this->redirectResponse('/development/dependency');
        }

        $callback = function () use ($request) {
            $module = $request->query->getAlpha(static::QUERY_KEY_MODULE, '*');

            if ($request->query->getBoolean(static::QUERY_KEY_BUILD_TREE, !$this->hasDependencyTreeCache())) {
                $this->getFacade()->buildDependencyTree($module);
            }

            echo $this->getFacade()->drawDetailedDependencyTreeGraph($module);
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
            $module = $request->query->getAlpha(static::QUERY_KEY_MODULE, '*');
            $showEngineBundle = $request->query->getBoolean('show-engine-bundle', true);

            if ($request->query->getBoolean(static::QUERY_KEY_BUILD_TREE, !$this->hasDependencyTreeCache())) {
                $this->getFacade()->buildDependencyTree($module);
            }

            echo $this->getFacade()->drawSimpleDependencyTreeGraph($showEngineBundle, $module);
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
            $this->getFacade()->buildDependencyTree('*');
        }

        $matrixData = $this->getFacade()->getAdjacencyMatrixData();
        $engineBundleList = $this->getFacade()->getEngineModuleList();

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
            $module = $request->query->getAlpha(static::QUERY_KEY_MODULE, '*');

            if ($request->query->getBoolean(static::QUERY_KEY_BUILD_TREE, !$this->hasDependencyTreeCache())) {
                $this->getFacade()->buildDependencyTree($module);
            }

            echo $this->getFacade()->drawExternalDependencyTreeGraph($module);
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
