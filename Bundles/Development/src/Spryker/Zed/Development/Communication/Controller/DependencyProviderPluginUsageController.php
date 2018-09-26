<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Controller;

use Generated\Shared\Transfer\ModuleFilterTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 */
class DependencyProviderPluginUsageController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        return $this->viewResponse([
            'projectModules' => $this->getFacade()->getProjectModules(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function detailsAction(Request $request): array
    {
        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer->setName($request->query->getAlnum('organization'));

        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer->setName($request->query->getAlnum('module'));

        $moduleFilter = new ModuleFilterTransfer();
        $moduleFilter
            ->setModule($moduleTransfer)
            ->setOrganization($organizationTransfer);

        $dependencyProviderPluginUsages = $this->getFacade()->getInProjectDependencyProviderUsedPlugins($moduleFilter);

        return $this->viewResponse([
            'dependencyProviderPluginUsages' => $dependencyProviderPluginUsages,
        ]);
    }

    /**
     * @return array
     */
    public function allAction()
    {
        return $this->viewResponse([
            'dependencyProviderPluginUsages' => $this->getFacade()->getInProjectDependencyProviderUsedPlugins(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadCsvAction(): StreamedResponse
    {
        $dependencyProviderPluginUsages = $this->getFacade()->getInProjectDependencyProviderUsedPlugins();

        $streamCsv = function () use ($dependencyProviderPluginUsages) {
            $resource = fopen('php://output', 'w');
            $header = ['organization', 'application', 'module', 'dependency_provider_class_name', 'plugin_organization', 'plugin_application', 'plugin_module', 'plugin_class_name'];
            fputcsv($resource, $header);
            foreach ($dependencyProviderPluginUsages->getDependencyProvider() as $dependencyProviderTransfer) {
                foreach ($dependencyProviderTransfer->getUsedPlugins() as $pluginTransfer) {
                    $row = [
                        'organization' => $dependencyProviderTransfer->getModule()->getOrganization()->getName(),
                        'application' => $dependencyProviderTransfer->getModule()->getApplication()->getName(),
                        'module' => $dependencyProviderTransfer->getModule()->getName(),
                        'dependency_provider_class_name' => $dependencyProviderTransfer->getFullyQualifiedClassName(),
                        'plugin_organization' => $pluginTransfer->getModule()->getOrganization()->getName(),
                        'plugin_application' => $pluginTransfer->getModule()->getApplication()->getName(),
                        'plugin_module' => $pluginTransfer->getModule()->getName(),
                        'plugin_class_name' => $pluginTransfer->getFullyQualifiedClassName(),
                    ];
                    fputcsv($resource, $row);
                }
            }
            fclose($resource);
        };

        return $this->streamedResponse($streamCsv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename=used_plugins.csv',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public',
        ]);
    }
}
