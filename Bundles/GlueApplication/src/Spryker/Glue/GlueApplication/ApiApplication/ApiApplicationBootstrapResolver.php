<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\ApiApplication;

use Generated\Shared\Transfer\GlueApiContextTransfer;
use Spryker\Glue\GlueApplication\Exception\MissingApiApplicationException;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface;

class ApiApplicationBootstrapResolver implements ApiApplicationBootstrapResolverInterface
{
    /**
     * @var array<string>
     */
    protected array $glueApplicationBootstrapPluginClassNames;

    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface>
     */
    protected array $glueApplicationBootstrapPlugins;

    /**
     * @param array<string> $glueApplicationBootstrapPluginClassNames
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface> $glueApplicationBootstrapPlugins
     */
    public function __construct(array $glueApplicationBootstrapPluginClassNames, array $glueApplicationBootstrapPlugins)
    {
        $this->glueApplicationBootstrapPluginClassNames = $glueApplicationBootstrapPluginClassNames;
        $this->glueApplicationBootstrapPlugins = $glueApplicationBootstrapPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueApiContextTransfer $apiApplicationContext
     *
     * @throws \Spryker\Glue\GlueApplication\Exception\MissingApiApplicationException
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface
     */
    public function resolveApiApplicationBootstrap(GlueApiContextTransfer $apiApplicationContext): GlueApplicationBootstrapPluginInterface
    {
        if (count($this->glueApplicationBootstrapPluginClassNames)) {
            return $this->resolveInjectedApiApplication($apiApplicationContext);
        }

        foreach ($this->glueApplicationBootstrapPlugins as $glueApplicationBootstrapPlugin) {
            if ($glueApplicationBootstrapPlugin->isServing($apiApplicationContext) === true) {
                return $glueApplicationBootstrapPlugin;
            }
        }

        throw new MissingApiApplicationException('No BootstrapPlugin matched for the current request');
    }

    /**
     * @param \Generated\Shared\Transfer\GlueApiContextTransfer $apiApplicationContext
     *
     * @throws \Spryker\Glue\GlueApplication\Exception\MissingApiApplicationException
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface
     */
    protected function resolveInjectedApiApplication(GlueApiContextTransfer $apiApplicationContext): GlueApplicationBootstrapPluginInterface
    {
        if (
            count($this->glueApplicationBootstrapPluginClassNames) === 1 &&
            class_exists($this->glueApplicationBootstrapPluginClassNames[0]) &&
            is_subclass_of($this->glueApplicationBootstrapPluginClassNames[0], GlueApplicationBootstrapPluginInterface::class)
        ) {
            return new $this->glueApplicationBootstrapPluginClassNames[0]();
        }

        foreach ($this->glueApplicationBootstrapPluginClassNames as $glueApplicationBootstrapPluginClassName) {
            if (
                class_exists($glueApplicationBootstrapPluginClassName)
                && is_subclass_of($glueApplicationBootstrapPluginClassName, GlueApplicationBootstrapPluginInterface::class)
            ) {
                /** @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface $apiApplicationPlugin */
                $apiApplicationPlugin = new $glueApplicationBootstrapPluginClassName();
                if ($apiApplicationPlugin->isServing($apiApplicationContext) === true) {
                    return $apiApplicationPlugin;
                }
            }
        }

        throw new MissingApiApplicationException('No BootstrapPlugin matched for the current request');
    }
}
