<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\ApiApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAgnosticApiApplication;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication;
use Spryker\Glue\GlueApplication\Exception\MissingApiConventionException;
use Spryker\Glue\GlueApplication\Exception\MissingCommunicationProtocolException;
use Spryker\Glue\GlueApplication\Exception\UnknownRequestFlowImplementationException;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface;
use Spryker\Shared\Application\ApplicationInterface;

class ApiApplicationProxy implements ApplicationInterface
{
    protected GlueApplicationBootstrapPluginInterface $glueApplicationBootstrapPlugin;

    protected RequestFlowExecutorInterface $requestFlowExecutor;

    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface>
     */
    protected array $communicationProtocolPlugins;

    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface>
     */
    protected array $apiConventionPlugins;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface $glueApplicationBootstrapPlugin
     * @param \Spryker\Glue\GlueApplication\ApiApplication\RequestFlowExecutorInterface $requestFlowExecutor
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface> $communicationProtocolPlugins
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface> $apiConventionPlugins
     */
    public function __construct(
        GlueApplicationBootstrapPluginInterface $glueApplicationBootstrapPlugin,
        RequestFlowExecutorInterface $requestFlowExecutor,
        array $communicationProtocolPlugins,
        array $apiConventionPlugins
    ) {
        $this->glueApplicationBootstrapPlugin = $glueApplicationBootstrapPlugin;
        $this->requestFlowExecutor = $requestFlowExecutor;
        $this->communicationProtocolPlugins = $communicationProtocolPlugins;
        $this->apiConventionPlugins = $apiConventionPlugins;
    }

    /**
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function boot(): ApplicationInterface
    {
        $this->glueApplicationBootstrapPlugin->getApplication()->boot();

        return $this;
    }

    /**
     * @throws \Spryker\Glue\GlueApplication\Exception\UnknownRequestFlowImplementationException
     *
     * @return void
     */
    public function run(): void
    {
        $bootstrapApplication = $this->glueApplicationBootstrapPlugin->getApplication();

        if ($bootstrapApplication instanceof RequestFlowAgnosticApiApplication) {
            $bootstrapApplication->run();

            return;
        }

        if ($bootstrapApplication instanceof RequestFlowAwareApiApplication) {
            $communicationProtocolPlugin = $this->resolveCommunicationPlugin($this->communicationProtocolPlugins);
            $glueRequestTransfer = $communicationProtocolPlugin->extractRequest(new GlueRequestTransfer());
            $apiConventionPlugin = $this->resolveApiConvention($this->apiConventionPlugins, $glueRequestTransfer);

            $glueResponseTransfer = $this->requestFlowExecutor->executeRequestFlow(
                $bootstrapApplication,
                $apiConventionPlugin,
                $glueRequestTransfer,
            );
            $communicationProtocolPlugin->sendResponse($glueResponseTransfer);

            return;
        }

        throw new UnknownRequestFlowImplementationException(sprintf(
            '%s needs to implement either %s or %s',
            get_class($bootstrapApplication),
            RequestFlowAgnosticApiApplication::class,
            RequestFlowAwareApiApplication::class,
        ));
    }

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface> $apiConventionPlugins
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @throws \Spryker\Glue\GlueApplication\Exception\MissingApiConventionException
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface
     */
    protected function resolveApiConvention(array $apiConventionPlugins, GlueRequestTransfer $glueRequestTransfer): ApiConventionPluginInterface
    {
        foreach ($apiConventionPlugins as $apiConventionPlugin) {
            if ($apiConventionPlugin->isApplicable($glueRequestTransfer)) {
                return $apiConventionPlugin;
            }
        }

        throw new MissingApiConventionException(
            sprintf(
                'No plugin that implements `%s` was found for the current request.
                Please implement one and inject into `GlueApplicationDependencyProvider::getApiConventionPlugins()`',
                ApiConventionPluginInterface::class,
            ),
        );
    }

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface> $communicationProtocolPlugins
     *
     * @throws \Spryker\Glue\GlueApplication\Exception\MissingCommunicationProtocolException
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface
     */
    protected function resolveCommunicationPlugin(array $communicationProtocolPlugins): CommunicationProtocolPluginInterface
    {
        foreach ($communicationProtocolPlugins as $communicationProtocolPlugin) {
            if ($communicationProtocolPlugin->isApplicable()) {
                return $communicationProtocolPlugin;
            }
        }

        throw new MissingCommunicationProtocolException(
            sprintf(
                'No communication protocol that implements `%s` was found for the current request.
                Please implement one and inject into `GlueApplicationDependencyProvider::getCommunicationProtocolPlugins()`',
                CommunicationProtocolPluginInterface::class,
            ),
        );
    }
}
