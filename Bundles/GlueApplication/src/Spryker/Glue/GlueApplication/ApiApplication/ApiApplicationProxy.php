<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\ApiApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAgnosticApiApplication;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication;
use Spryker\Glue\GlueApplication\Exception\UnknownRequestFlowImplementationException;
use Spryker\Glue\GlueApplication\Http\Request\RequestBuilderInterface;
use Spryker\Glue\GlueApplication\Http\Response\HttpSenderInterface;
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
     * @var \Spryker\Glue\GlueApplication\Http\Request\RequestBuilderInterface
     */
    protected RequestBuilderInterface $requestBuilder;

    /**
     * @var \Spryker\Glue\GlueApplication\Http\Response\HttpSenderInterface
     */
    protected HttpSenderInterface $httpSender;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface $glueApplicationBootstrapPlugin
     * @param \Spryker\Glue\GlueApplication\ApiApplication\RequestFlowExecutorInterface $requestFlowExecutor
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface> $communicationProtocolPlugins
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface> $apiConventionPlugins
     * @param \Spryker\Glue\GlueApplication\Http\Request\RequestBuilderInterface $requestBuilder
     * @param \Spryker\Glue\GlueApplication\Http\Response\HttpSenderInterface $httpSender
     */
    public function __construct(
        GlueApplicationBootstrapPluginInterface $glueApplicationBootstrapPlugin,
        RequestFlowExecutorInterface $requestFlowExecutor,
        array $communicationProtocolPlugins,
        array $apiConventionPlugins,
        RequestBuilderInterface $requestBuilder,
        HttpSenderInterface $httpSender
    ) {
        $this->glueApplicationBootstrapPlugin = $glueApplicationBootstrapPlugin;
        $this->communicationProtocolPlugins = $communicationProtocolPlugins;
        $this->requestFlowExecutor = $requestFlowExecutor;
        $this->conventionPlugins = $apiConventionPlugins;
        $this->requestBuilder = $requestBuilder;
        $this->httpSender = $httpSender;
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
            $glueRequestTransfer = $this->extractRequest($communicationProtocolPlugin);

            $apiConventionPlugin = $this->resolveConvention($this->conventionPlugins, $glueRequestTransfer);

            $glueResponseTransfer = $this->requestFlowExecutor->executeRequestFlow(
                $glueRequestTransfer,
                $bootstrapApplication,
                $apiConventionPlugin,
            );

            $this->sendResponse($glueResponseTransfer, $communicationProtocolPlugin);

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
     * @deprecated Will be removed in the next major. Exists for BC-reason only.
     *
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface> $communicationProtocolPlugins
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface|null
     */
    protected function resolveCommunicationPlugin(array $communicationProtocolPlugins): ?CommunicationProtocolPluginInterface
    {
        foreach ($communicationProtocolPlugins as $communicationProtocolPlugin) {
            if ($communicationProtocolPlugin->isApplicable()) {
                return $communicationProtocolPlugin;
            }
        }

        return null;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface|null $communicationProtocolPlugin
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function extractRequest(?CommunicationProtocolPluginInterface $communicationProtocolPlugin): GlueRequestTransfer
    {
        if ($communicationProtocolPlugin !== null) {
            return $communicationProtocolPlugin->extractRequest(new GlueRequestTransfer());
        }

        return $this->requestBuilder->extract(new GlueRequestTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface|null $communicationProtocolPlugin
     *
     * @return void
     */
    protected function sendResponse(
        GlueResponseTransfer $glueResponseTransfer,
        ?CommunicationProtocolPluginInterface $communicationProtocolPlugin
    ): void {
        if ($communicationProtocolPlugin !== null) {
            $communicationProtocolPlugin->sendResponse($glueResponseTransfer);

            return;
        }

        $this->httpSender->sendResponse($glueResponseTransfer);
    }
}
