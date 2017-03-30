<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\ApiDependencyProvider;
use Spryker\Zed\Api\Business\Exception\FormatterNotFoundException;
use Spryker\Zed\Api\Business\Model\Dispatcher;
use Spryker\Zed\Api\Business\Model\Formatter\JsonFormatter;
use Spryker\Zed\Api\Business\Model\Processor;
use Spryker\Zed\Api\Business\Model\Processor\Post\Action\AddActionPostProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Post\Action\DeleteActionPostProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Action\AddActionPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Action\FindActionPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Action\GetActionPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Action\UpdateActionPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Fields\FieldsByQueryPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\FilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Header\PaginationByHeaderFilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\CriteriaByQueryFilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\PaginationByQueryFilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\SortByQueryFilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Format\FormatTypeByHeaderPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Format\FormatTypeByPathPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PaginationPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PathPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourceActionPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourceParametersPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourcePreProcessor;
use Spryker\Zed\Api\Business\Model\ResourceHandler;
use Spryker\Zed\Api\Business\Model\Transformer;
use Spryker\Zed\Api\Business\Model\Validator\Validator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 * @method \Spryker\Zed\Api\Persistence\ApiQueryContainer getQueryContainer()
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
     * @param string $formatType
     *
     * @throws \Spryker\Zed\Api\Business\Exception\FormatterNotFoundException
     *
     * @return \Spryker\Zed\Api\Business\Model\Formatter\JsonFormatter
     */
    public function createFormatter($formatType)
    {
        switch ($formatType) {
            case 'json':
                return new JsonFormatter($this->getUtilEncoding());
        }

        throw new FormatterNotFoundException(sprintf('Formatter for %s not found', $formatType));
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\ResourceHandlerInterface
     */
    public function createResourceHandler()
    {
        return new ResourceHandler(
            $this->getApiPluginStack()
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\ProcessorInterface
     */
    public function createProcessor()
    {
        return new Processor(
            $this->getPreProcessorPluginStack(),
            $this->getPostProcessorPluginStack()
        );
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingService
     */
    protected function getUtilEncoding()
    {
        return $this->getProvidedDependency(ApiDependencyProvider::SERVICE_ENCODING);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Spryker\Zed\Api\Business\Model\Transformer
     */
    public function createTransformer(ApiRequestTransfer $apiRequestTransfer)
    {
        return new Transformer(
            $this->createFormatter($apiRequestTransfer->getFormatType())
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Validator\ValidatorInterface
     */
    public function createValidator()
    {
        return new Validator(
            $this->getValidatorPluginStack()
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Validator\ValidatorInterface[]
     */
    protected function getValidatorPluginStack()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Api\Dependency\Plugin\ApiPluginInterface[]
     */
    protected function getApiPluginStack()
    {
        return $this->getProvidedDependency(ApiDependencyProvider::PLUGINS_API);
    }

    /**
     * @return \Spryker\Zed\Api\Dependency\Plugin\ApiPreProcessorPluginInterface[]
     */
    protected function getPreProcessorPluginStack()
    {
        return [
            $this->createPathPreProcessor(),
            $this->createFormatTypeByHeaderPreProcessor(),
            $this->createFormatTypeByPathPreProcessor(),
            $this->createResourcePreProcessor(),
            $this->createResourceActionPreProcessor(),
            $this->createResourceParametersPreProcessor(),
            $this->createFilterPreProcessor(),
            $this->createPaginationPreProcessor(),
            $this->createFieldsByQueryPreProcessor(),
            $this->createSortByQueryFilterPreProcessor(),
            $this->createCriteriaByQueryFilterPreProcessor(),
            $this->createPaginationByQueryFilterPreProcessor(),
            $this->createPaginationByHeaderFilterPreProcessor(),
            $this->createAddActionPreProcessor(),
            $this->createUpdateActionPreProcessor(),
            $this->createFindActionPreProcessor(),
            $this->createFindActionPreProcessor(),
        ];
    }

    /**
     * @return \Spryker\Zed\Api\Dependency\Plugin\ApiPostProcessorPluginInterface[]
     */
    protected function getPostProcessorPluginStack()
    {
        return [
            $this->createAddActionPostProcessor(),
            $this->createDeleteActionPostProcessor(),
        ];
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PathPreProcessor
     */
    protected function createPathPreProcessor()
    {
        return new PathPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Format\FormatTypeByHeaderPreProcessor
     */
    protected function createFormatTypeByHeaderPreProcessor()
    {
        return new FormatTypeByHeaderPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Format\FormatTypeByPathPreProcessor
     */
    protected function createFormatTypeByPathPreProcessor()
    {
        return new FormatTypeByPathPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourcePreProcessor
     */
    protected function createResourcePreProcessor()
    {
        return new ResourcePreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourceActionPreProcessor
     */
    protected function createResourceActionPreProcessor()
    {
        return new ResourceActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourceParametersPreProcessor
     */
    protected function createResourceParametersPreProcessor()
    {
        return new ResourceParametersPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\FilterPreProcessor
     */
    protected function createFilterPreProcessor()
    {
        return new FilterPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PaginationPreProcessor
     */
    protected function createPaginationPreProcessor()
    {
        return new PaginationPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Fields\FieldsByQueryPreProcessor
     */
    protected function createFieldsByQueryPreProcessor()
    {
        return new FieldsByQueryPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\SortByQueryFilterPreProcessor
     */
    protected function createSortByQueryFilterPreProcessor()
    {
        return new SortByQueryFilterPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\CriteriaByQueryFilterPreProcessor
     */
    protected function createCriteriaByQueryFilterPreProcessor()
    {
        return new CriteriaByQueryFilterPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\PaginationByQueryFilterPreProcessor
     */
    protected function createPaginationByQueryFilterPreProcessor()
    {
        return new PaginationByQueryFilterPreProcessor(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Header\PaginationByHeaderFilterPreProcessor
     */
    protected function createPaginationByHeaderFilterPreProcessor()
    {
        return new PaginationByHeaderFilterPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Action\AddActionPreProcessor
     */
    protected function createAddActionPreProcessor()
    {
        return new AddActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Action\UpdateActionPreProcessor
     */
    protected function createUpdateActionPreProcessor()
    {
        return new UpdateActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Action\GetActionPreProcessor
     */
    protected function createGetActionPreProcessor()
    {
        return new GetActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Action\FindActionPreProcessor
     */
    protected function createFindActionPreProcessor()
    {
        return new FindActionPreProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\Action\AddActionPostProcessor
     */
    protected function createAddActionPostProcessor()
    {
        return new AddActionPostProcessor();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\Action\DeleteActionPostProcessor
     */
    protected function createDeleteActionPostProcessor()
    {
        return new DeleteActionPostProcessor();
    }

}
