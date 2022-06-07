<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication;

use Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientBridge;
use Spryker\Glue\GlueApplication\Dependency\External\GlueApplicationToSymfonyFilesystemAdapter;
use Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToUtilEncodingServiceBridge;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\FallbackStorefrontApiGlueApplicationBootstrapPlugin;
use Spryker\Glue\GlueApplication\Rest\Collection\ResourceRelationshipCollection;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Shared\Kernel\Container\GlobalContainer;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationConfig getConfig()
 */
class GlueApplicationDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGIN_RESOURCE_ROUTES = 'PLUGIN_RESOURCE_ROUTES';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGIN_RESOURCE_RELATIONSHIP = 'PLUGIN_RESOURCE_RELATIONSHIP';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGIN_VALIDATE_HTTP_REQUEST = 'PLUGIN_VALIDATE_HTTP_REQUEST';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGIN_FORMATTED_CONTROLLER_BEFORE_ACTION = 'PLUGIN_FORMATTED_CONTROLLER_BEFORE_ACTION';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGIN_VALIDATE_REST_REQUEST = 'PLUGIN_VALIDATE_REST_REQUEST';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGINS_VALIDATE_REST_USER = 'PLUGIN_VALIDATE_REST_USER';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGIN_REST_REQUEST_VALIDATOR = 'PLUGIN_REST_REQUEST_VALIDATOR';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGIN_FORMAT_REQUEST = 'PLUGIN_FORMAT_REQUEST';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGIN_FORMAT_RESPONSE_DATA = 'PLUGIN_FORMAT_RESPONSE_DATA';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGIN_FORMAT_RESPONSE_HEADERS = 'PLUGIN_FORMAT_RESPONSE_HEADERS';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGIN_CONTROLLER_BEFORE_ACTION = 'PLUGIN_CONTROLLER_BEFORE_ACTION';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGIN_CONTROLLER_AFTER_ACTION = 'PLUGIN_CONTROLLER_AFTER_ACTION';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGINS_APPLICATION = 'PLUGINS_APPLICATION';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGINS_REST_USER_FINDER = 'PLUGINS_REST_USER_FINDER';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGINS_ROUTER_PARAMETER_EXPANDER = 'PLUGINS_ROUTER_PARAMETER_EXPANDER';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const APPLICATION_GLUE = 'APPLICATION_GLUE';

    /**
     * @var string
     */
    public const PLUGINS_GLUE_APPLICATION_BOOTSTRAP = 'PLUGINS_GLUE_APPLICATION_BOOTSTRAP';

    /**
     * @var string
     */
    public const PLUGIN_API_CONTEXT_EXPANDER = 'PLUGIN_API_CONTEXT_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_COMMUNICATION_PROTOCOL = 'PLUGINS_COMMUNICATION_PROTOCOL';

    /**
     * @var string
     */
    public const PLUGINS_API_CONVENTION = 'PLUGINS_API_CONVENTION';

    /**
     * @var string
     */
    public const PLUGINS_RESOURCE_FILTER = 'PLUGINS_RESOURCE_FILTER';

    /**
     * @var string
     */
    public const PLUGINS_CONTROLLER_CACHE_COLLECTOR = 'PLUGINS_CONTROLLER_CACHE_COLLECTOR';

    /**
     * @var string
     */
    public const FILESYSTEM = 'FILESYSTEM';

    /**
     * @var string
     */
    public const PLUGINS_GLUE_APPLICATION_ROUTER_PROVIDER = 'PLUGINS_GLUE_APPLICATION_ROUTER_PROVIDER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addUtilEncodingService($container);
        $container = $this->addGlueApplication($container);
        $container = $this->addStoreClient($container);
        $container = $this->addResourceRoutePlugins($container);
        $container = $this->addResourceRelationshipPlugins($container);
        $container = $this->addValidateHttpRequestPlugins($container);
        $container = $this->addFormattedControllerBeforeActionTerminatePlugins($container);
        $container = $this->addValidateRestRequestPlugins($container);
        $container = $this->addRestUserValidatorPlugins($container);
        $container = $this->addRestRequestValidatorPlugins($container);
        $container = $this->addFormatRequestPlugins($container);
        $container = $this->addFormatResponseDataPlugins($container);
        $container = $this->addFormatResponseHeadersPlugins($container);
        $container = $this->addControllerBeforeActionPlugins($container);
        $container = $this->addControllerAfterActionPlugins($container);
        $container = $this->addApplicationPlugins($container);
        $container = $this->addRestUserFinderPlugins($container);
        $container = $this->addRouterParameterExpanderPlugins($container);

        $container = $this->addGlueContextExpanderPlugins($container);
        $container = $this->addGlueApplicationBootstrapPlugins($container);
        $container = $this->addGlueApplicationBootstrapPlugins($container);
        $container = $this->addGlueContextExpanderPlugins($container);
        $container = $this->addCommunicationProtocolPlugins($container);
        $container = $this->addApiConventionPlugins($container);
        $container = $this->addResourceFilterPlugins($container);
        $container = $this->addControllerCacheCollectorPlugins($container);
        $container = $this->addFilesystem($container);
        $container = $this->addGlueApplicationRouterProviderPlugins($container);

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlueApplication(Container $container): Container
    {
        $container->set(static::APPLICATION_GLUE, function (Container $container) {
            return (new GlobalContainer())->getContainer();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new GlueApplicationToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addResourceRoutePlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_RESOURCE_ROUTES, function (Container $container) {
            return $this->getResourceRoutePlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addResourceRelationshipPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_RESOURCE_RELATIONSHIP, function (Container $container) {
            return $this->getResourceRelationshipPlugins(new ResourceRelationshipCollection());
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addValidateHttpRequestPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_VALIDATE_HTTP_REQUEST, function (Container $container) {
            return $this->getValidateHttpRequestPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addFormattedControllerBeforeActionTerminatePlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_FORMATTED_CONTROLLER_BEFORE_ACTION, function (Container $container) {
            return $this->getFormattedControllerBeforeActionTerminatePlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addValidateRestRequestPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_VALIDATE_REST_REQUEST, function (Container $container) {
            return $this->getValidateRestRequestPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestUserValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_VALIDATE_REST_USER, function (Container $container) {
            return $this->getRestUserValidatorPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestRequestValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_REST_REQUEST_VALIDATOR, function (Container $container) {
            return $this->getRestRequestValidatorPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addFormatRequestPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_FORMAT_REQUEST, function (Container $container) {
            return $this->getFormatRequestPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addFormatResponseDataPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_FORMAT_RESPONSE_DATA, function (Container $container) {
            return $this->getFormatResponseDataPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addFormatResponseHeadersPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_FORMAT_RESPONSE_HEADERS, function (Container $container) {
            return $this->getFormatResponseHeadersPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new GlueApplicationToStoreClientBridge($container->getLocator()->store()->client());
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addControllerBeforeActionPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_CONTROLLER_BEFORE_ACTION, function (Container $container) {
            return $this->getControllerBeforeActionPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addControllerAfterActionPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_CONTROLLER_AFTER_ACTION, function (Container $container) {
            return $this->getControllerAfterActionPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addApplicationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_APPLICATION, function (Container $container): array {
            return $this->getApplicationPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestUserFinderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REST_USER_FINDER, function (Container $container) {
            return $this->getRestUserFinderPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRouterParameterExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ROUTER_PARAMETER_EXPANDER, function (Container $container) {
            return $this->getRouterParameterExpanderPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface>
     */
    protected function getApplicationPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * Rest resource route plugin stack
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface>
     */
    protected function getResourceRoutePlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * Rest resource relation provider plugin collection, plugins must construct full resource by resource ids.
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface $resourceRelationshipCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    protected function getResourceRelationshipPlugins(
        ResourceRelationshipCollectionInterface $resourceRelationshipCollection
    ): ResourceRelationshipCollectionInterface {
        return $resourceRelationshipCollection;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * Validate http request plugins
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface>
     */
    protected function getValidateHttpRequestPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * Plugins that called before processing {@link \Spryker\Glue\Kernel\Controller\FormattedAbstractController}.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormattedControllerBeforeActionPluginInterface>
     */
    protected function getFormattedControllerBeforeActionTerminatePlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * Format/Parse http request to internal rest resource request
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatRequestPluginInterface>
     */
    protected function getFormatRequestPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * Format response data the data which will send with http response
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseDataPluginInterface>
     */
    protected function getFormatResponseDataPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * Format/add additional response headers
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseHeadersPluginInterface>
     */
    protected function getFormatResponseHeadersPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateRestRequestPluginInterface>
     */
    protected function getValidateRestRequestPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestRequestValidatorPluginInterface>
     */
    protected function getRestRequestValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserValidatorPluginInterface>
     */
    protected function getRestUserValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * Called before invoking controller action
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerBeforeActionPluginInterface>
     */
    protected function getControllerBeforeActionPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * Called after done processing controller action
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerAfterActionPluginInterface>
     */
    protected function getControllerAfterActionPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserFinderPluginInterface>
     */
    protected function getRestUserFinderPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouterParameterExpanderPluginInterface>
     */
    protected function getRouterParameterExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlueApplicationBootstrapPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_GLUE_APPLICATION_BOOTSTRAP, function (Container $container) {
            return $this->getGlueApplicationBootstrapPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface>
     */
    protected function getGlueApplicationBootstrapPlugins(): array
    {
        return [
            new FallbackStorefrontApiGlueApplicationBootstrapPlugin(),
        ];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlueContextExpanderPlugins(Container $container)
    {
        $container->set(static::PLUGIN_API_CONTEXT_EXPANDER, function (Container $container) {
            return $this->getGlueContextExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueContextExpanderPluginInterface>
     */
    protected function getGlueContextExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCommunicationProtocolPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_COMMUNICATION_PROTOCOL, function (Container $container) {
            return $this->getCommunicationProtocolPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface>
     */
    protected function getCommunicationProtocolPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addApiConventionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_API_CONVENTION, function () {
            return $this->getApiConventionPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface>
     */
    protected function getApiConventionPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addResourceFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RESOURCE_FILTER, function () {
            return $this->getResourceFilterPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceFilterPluginInterface>
     */
    public function getResourceFilterPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addControllerCacheCollectorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONTROLLER_CACHE_COLLECTOR, function () {
            return $this->getControllerCacheCollectorPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerCacheCollectorPluginInterface>
     */
    protected function getControllerCacheCollectorPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addFilesystem(Container $container): Container
    {
        $container->set(static::FILESYSTEM, function () {
            return new GlueApplicationToSymfonyFilesystemAdapter();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlueApplicationRouterProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_GLUE_APPLICATION_ROUTER_PROVIDER, function () {
            return $this->getGlueApplicationRouterProviderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiApplicationEndpointProviderPluginInterface>
     */
    protected function getGlueApplicationRouterProviderPlugins(): array
    {
        return [];
    }
}
