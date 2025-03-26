<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business\DataEntityPluginProvider;

use Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityPluginInterface;

interface DataExportPluginProviderInterface
{
    /**
     * @param string $dataEntityName
     * @param string|null $pluginInterface
     *
     * @return bool
     */
    public function hasDataEntityPlugin(string $dataEntityName, ?string $pluginInterface = null): bool;

    /**
     * @param string $dataEntityName
     *
     * @throws \Spryker\Zed\DataExport\Business\Exception\DataExporterNotFoundException
     *
     * @return void
     */
    public function requireDataEntityPlugin(string $dataEntityName): void;

    /**
     * @param string $dataEntityName
     * @param class-string<\Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityPluginInterface> $pluginInterface
     *
     * @return \Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityPluginInterface
     */
    public function getDataEntityPluginForInterface(string $dataEntityName, string $pluginInterface): DataEntityPluginInterface;

    /**
     * @param string $dataEntityName
     *
     * @return \Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityPluginInterface|false
     */
    public function findDataEntityPlugin(string $dataEntityName): DataEntityPluginInterface|false;
}
