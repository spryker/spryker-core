<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business;

interface MaintenanceFacadeInterface
{

    /**
     * @api
     *
     * @param string $bundleName
     *
     * @return array
     */
    public function showOutgoingDependenciesForBundle($bundleName);

    /**
     * @api
     *
     * @param string $bundleName
     *
     * @return array
     */
    public function showIncomingDependenciesForBundle($bundleName);

    /**
     * @api
     *
     * @return array
     */
    public function getAllBundles();

    /**
     * @api
     *
     * @param string $application
     * @param string $bundle
     * @param string $layer
     *
     * @return void
     */
    public function buildDependencyTree($application, $bundle, $layer);

    /**
     * @api
     *
     * @param string|bool $bundleToView
     *
     * @return bool
     */
    public function drawDetailedDependencyTreeGraph($bundleToView);

    /**
     * @api
     *
     * @param string|bool $bundleToView
     *
     * @return bool
     */
    public function drawSimpleDependencyTreeGraph($bundleToView);

    /**
     * @api
     *
     * @return bool
     */
    public function getAdjacencyMatrixData();

    /**
     * @api
     *
     * @return array
     */
    public function getDependencyViolations();

    /**
     * @api
     *
     * @return array
     */
    public function getEngineBundleList();

    /**
     * @api
     *
     * @return void
     */
    public function updateComposerJsonInBundles();

}
