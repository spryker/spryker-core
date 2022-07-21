<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\ApiApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAgnosticApiApplication;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication;
use Spryker\Glue\GlueApplication\Exception\MissingCommunicationProtocolException;
use Spryker\Glue\GlueApplication\Exception\UnknownRequestFlowImplementationException;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface;
use Spryker\Shared\Application\ApplicationInterface;

class ApiApplicationProxy implements ApplicationInterface
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface
     */
    protected GlueApplicationBootstrapPluginInterface $glueApplicationBootstrapPlugin;

    /**
     * @var \Spryker\Glue\GlueApplication\ApiApplication\RequestFlowExecutorInterface
     */
    protected RequestFlowExecutorInterface $requestFlowExecutor;

    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface>
     */
    protected array $communicationProtocolPlugins;

    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface>
     */
    protected array $conventionPlugins;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface $glueApplicationBootstrapPlugin
     * @param \Spryker\Glue\GlueApplication\ApiApplication\RequestFlowExecutorInterface $requestFlowExecutor
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface> $communicationProtocolPlugins
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface> $apiConventionPlugins
     */
    public function __construct(
        GlueApplicationBootstrapPluginInterface $glueApplicationBootstrapPlugin,
        RequestFlowExecutorInterface $requestFlowExecutor,
        array $communicationProtocolPlugins,
        array $apiConventionPlugins
    ) {
        $this->glueApplicationBootstrapPlugin = $glueApplicationBootstrapPlugin;
        $this->communicationProtocolPlugins = $communicationProtocolPlugins;
        $this->requestFlowExecutor = $requestFlowExecutor;
        $this->conventionPlugins = $apiConventionPlugins;
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
            $apiConventionPlugin = $this->resolveConvention($this->conventionPlugins, $glueRequestTransfer);

            $glueResponseTransfer = $this->requestFlowExecutor->executeRequestFlow(
                $glueRequestTransfer,
                $bootstrapApplication,
                $apiConventionPlugin,
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
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface> $apiConventionPlugins
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null
     */
    protected function resolveConvention(array $apiConventionPlugins, GlueRequestTransfer $glueRequestTransfer): ?ConventionPluginInterface
    {
        foreach ($apiConventionPlugins as $apiConventionPlugin) {
            if ($apiConventionPlugin->isApplicable($glueRequestTransfer)) {
                return $apiConventionPlugin;
            }
        }

        return null;
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
