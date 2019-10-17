<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business;

use Spryker\Zed\Api\ApiDependencyProvider;
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
use Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourceActionPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourceIdPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourceParametersPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourcePreProcessor;
use Spryker\Zed\Api\Business\Model\ResourceHandler;
use Spryker\Zed\Api\Business\Model\Validator\ApiValidator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 * @method \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface getQueryContainer()
 */
class ApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Api\Business\Model\DispatcherInterface
     */
    public function createDispatcher()
    {
        return new Dispatcher(
            $this->createResourceHandler(),
            $this->createProcessor(),
            $this->createValidator()
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\ResourceHandlerInterface
     */
    public function createResourceHandler()
    {
        return new ResourceHandler(
            $this->getApiPlugins(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\ProcessorInterface
     */
    public function createProcessor()
    {
        return new Processor(
            $this->getPreProcessorStack(),
            $this->getPostProcessorStack()
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Validator\ApiValidatorInterface
     */
    public function createValidator()
    {
        return new ApiValidator(
            $this->getApiValidatorPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Api\Dependency\Plugin\ApiValidatorPluginInterface[]
     */
    protected function getApiValidatorPlugins()
    {
        return $this->getProvidedDependency(ApiDependencyProvider::PLUGINS_API_VALIDATOR);
    }

    /**
     * @return \Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface[]
     */
    protected function getApiPlugins()
    {
        return $this->getProvidedDependency(ApiDependencyProvider::PLUGINS_API);
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface[]
     */
    protected function getPreProcessorStack()
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
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface[]
     */
    protected function getPostProcessorStack()
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
    protected function createPathPreProcessor()
    {
        return new PathPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createFormatTypeByHeaderPreProcessor()
    {
        return new FormatTypeByHeaderPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createFormatTypeByPathPreProcessor()
    {
        return new FormatTypeByPathPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createResourcePreProcessor()
    {
        return new ResourcePreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createResourceIdPreProcessor()
    {
        return new ResourceIdPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createResourceActionPreProcessor()
    {
        return new ResourceActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createResourceParametersPreProcessor()
    {
        return new ResourceParametersPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createFilterPreProcessor()
    {
        return new FilterPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createFieldsByQueryPreProcessor()
    {
        return new FieldsByQueryPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createSortByQueryFilterPreProcessor()
    {
        return new SortByQueryFilterPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createCriteriaByQueryFilterPreProcessor()
    {
        return new CriteriaByQueryFilterPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createPaginationByQueryFilterPreProcessor()
    {
        return new PaginationByQueryFilterPreProcessor(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createPaginationByHeaderFilterPreProcessor()
    {
        return new PaginationByHeaderFilterPreProcessor(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createAddActionPreProcessor()
    {
        return new AddActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createUpdateActionPreProcessor()
    {
        return new UpdateActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createGetActionPreProcessor()
    {
        return new GetActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    protected function createFindActionPreProcessor()
    {
        return new FindActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface
     */
    protected function createAddActionPostProcessor()
    {
        return new AddActionPostProcessor(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface
     */
    protected function createRemoveActionPostProcessor()
    {
        return new RemoveActionPostProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface
     */
    protected function createOptionsActionPostProcessor()
    {
        return new OptionsActionPostProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface
     */
    protected function createPaginationByHeaderFilterPostProcessor()
    {
        return new PaginationByHeaderFilterPostProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface
     */
    protected function createCorsFilterPostProcessor()
    {
        return new CorsFilterPostProcessor(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface
     */
    protected function createFindActionPostProcessor()
    {
        return new FindActionPostProcessor(
            $this->getConfig()
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
     * @return \Spryker\Zed\Api\Communication\Plugin\ApiRequestTransferFilterPluginInterface[]
     */
    protected function getApiRequestTransferFilterPlugins()
    {
        return $this->getProvidedDependency(ApiDependencyProvider::PLUGINS_API_REQUEST_TRANSFER_FILTER);
    }
}
