<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business;

use Spryker\Zed\Api\ApiDependencyProvider;
use Spryker\Zed\Api\Business\Creator\ApiDataCreator;
use Spryker\Zed\Api\Business\Creator\ApiDataCreatorInterface;
use Spryker\Zed\Api\Business\Executor\ResourcePluginExecutor;
use Spryker\Zed\Api\Business\Mapper\ApiResponseMapper;
use Spryker\Zed\Api\Business\Mapper\ApiResponseMapperInterface;
use Spryker\Zed\Api\Business\Model\Dispatcher;
use Spryker\Zed\Api\Business\Model\Filter\ApiRequestTransferFilter;
use Spryker\Zed\Api\Business\Model\Processor;
use Spryker\Zed\Api\Business\Model\Processor\Post\Action\AddActionPostProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Post\Action\FindActionPostProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Post\Action\OptionsActionPostProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Post\Action\RemoveActionPostProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Post\Filter\Header\CorsFilterPostProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Post\Filter\Header\PaginationByHeaderFilterPostProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Action\AddActionPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Action\FindActionPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Action\GetActionPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Action\UpdateActionPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Header\PaginationByHeaderFilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\CriteriaByQueryFilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\FieldsByQueryPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\PaginationByQueryFilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\SortByQueryFilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\FilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Format\FormatTypeByHeaderPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Format\FormatTypeByPathPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PathPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\RestApiResource\ResourceActionPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\RestApiResource\ResourceIdPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\RestApiResource\ResourceParametersPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\RestApiResource\ResourcePreProcessor;
use Spryker\Zed\Api\Business\Model\Validator\ApiValidator;
use Spryker\Zed\Api\Business\Router\ApiRouter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 */
class ApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Api\Business\Model\DispatcherInterface
     */
    public function createDispatcher()
    {
        return new Dispatcher(
            $this->createResourcePluginExecutor(),
            $this->createProcessor(),
            $this->createValidator(),
            $this->createApiResponseMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Executor\ResourcePluginExecutorInterface
     */
    public function createResourcePluginExecutor()
    {
        return new ResourcePluginExecutor(
            $this->getApiPlugins(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\ProcessorInterface
     */
    public function createProcessor()
    {
        return new Processor(
            $this->getPreProcessorStack(),
            $this->getPostProcessorStack(),
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Validator\ApiValidatorInterface
     */
    public function createValidator()
    {
        return new ApiValidator(
            $this->getApiValidatorPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\ApiExtension\Dependency\Plugin\ApiValidatorPluginInterface>
     */
    public function getApiValidatorPlugins()
    {
        return $this->getProvidedDependency(ApiDependencyProvider::PLUGINS_API_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface>
     */
    public function getApiPlugins()
    {
        return $this->getProvidedDependency(ApiDependencyProvider::PLUGINS_API);
    }

    /**
     * @return array<\Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface>
     */
    public function getPreProcessorStack()
    {
        return [
            $this->createPathPreProcessor(),
            $this->createFormatTypeByHeaderPreProcessor(),
            $this->createFormatTypeByPathPreProcessor(),
            $this->createResourcePreProcessor(),
            $this->createResourceIdPreProcessor(),
            $this->createResourceActionPreProcessor(),
            $this->createResourceParametersPreProcessor(),

            $this->createFilterPreProcessor(),
            $this->createFieldsByQueryPreProcessor(),
            $this->createSortByQueryFilterPreProcessor(),
            $this->createCriteriaByQueryFilterPreProcessor(),
            $this->createPaginationByQueryFilterPreProcessor(),
            $this->createPaginationByHeaderFilterPreProcessor(),
            $this->createAddActionPreProcessor(),
            $this->createUpdateActionPreProcessor(),
            $this->createGetActionPreProcessor(),
            $this->createFindActionPreProcessor(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface>
     */
    public function getPostProcessorStack()
    {
        return [
            $this->createCorsFilterPostProcessor(),
            $this->createOptionsActionPostProcessor(),
            $this->createAddActionPostProcessor(),
            $this->createRemoveActionPostProcessor(),
            $this->createFindActionPostProcessor(),
            $this->createPaginationByHeaderFilterPostProcessor(),
        ];
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createPathPreProcessor()
    {
        return new PathPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createFormatTypeByHeaderPreProcessor()
    {
        return new FormatTypeByHeaderPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createFormatTypeByPathPreProcessor()
    {
        return new FormatTypeByPathPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createResourcePreProcessor()
    {
        return new ResourcePreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createResourceIdPreProcessor()
    {
        return new ResourceIdPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createResourceActionPreProcessor()
    {
        return new ResourceActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createResourceParametersPreProcessor()
    {
        return new ResourceParametersPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createFilterPreProcessor()
    {
        return new FilterPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createFieldsByQueryPreProcessor()
    {
        return new FieldsByQueryPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createSortByQueryFilterPreProcessor()
    {
        return new SortByQueryFilterPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createCriteriaByQueryFilterPreProcessor()
    {
        return new CriteriaByQueryFilterPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createPaginationByQueryFilterPreProcessor()
    {
        return new PaginationByQueryFilterPreProcessor(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createPaginationByHeaderFilterPreProcessor()
    {
        return new PaginationByHeaderFilterPreProcessor(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createAddActionPreProcessor()
    {
        return new AddActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createUpdateActionPreProcessor()
    {
        return new UpdateActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createGetActionPreProcessor()
    {
        return new GetActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function createFindActionPreProcessor()
    {
        return new FindActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface
     */
    public function createAddActionPostProcessor()
    {
        return new AddActionPostProcessor(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface
     */
    public function createRemoveActionPostProcessor()
    {
        return new RemoveActionPostProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface
     */
    public function createOptionsActionPostProcessor()
    {
        return new OptionsActionPostProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface
     */
    public function createPaginationByHeaderFilterPostProcessor()
    {
        return new PaginationByHeaderFilterPostProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface
     */
    public function createCorsFilterPostProcessor()
    {
        return new CorsFilterPostProcessor(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface
     */
    public function createFindActionPostProcessor()
    {
        return new FindActionPostProcessor(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Filter\ApiRequestTransferFilterInterface
     */
    public function createRequestTransferFilter()
    {
        return new ApiRequestTransferFilter($this->getApiRequestTransferFilterPlugins());
    }

    /**
     * @return array<\Spryker\Zed\Api\Communication\Plugin\ApiRequestTransferFilterPluginInterface>
     */
    public function getApiRequestTransferFilterPlugins()
    {
        return $this->getProvidedDependency(ApiDependencyProvider::PLUGINS_API_REQUEST_TRANSFER_FILTER);
    }

    /**
     * @return \Symfony\Component\Routing\RouterInterface
     */
    public function createApiRouter(): RouterInterface
    {
        return new ApiRouter($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Api\Business\Mapper\ApiResponseMapperInterface
     */
    public function createApiResponseMapper(): ApiResponseMapperInterface
    {
        return new ApiResponseMapper();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Creator\ApiDataCreatorInterface
     */
    public function createApiDataCreator(): ApiDataCreatorInterface
    {
        return new ApiDataCreator();
    }
}
