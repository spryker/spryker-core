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
     * @return \Spryker\Zed\Api\Business\Model\Dispatcher
     */
    public function createDispatcher()
    {
        return new Dispatcher(
            $this->getConfig(),
            $this->getConfig()->getPreProcessorStack(),
            $this->getConfig()->getPostProcessorStack(),
            $this->createValidator(),
            $this->createResourceHandler()
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
        return $this->getProvidedDependency(ApiDependencyProvider::PLUGIN_STACK_API);
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface[]
     */
    protected function getPreProcessorStack()
    {
        return $this->getProvidedDependency(ApiDependencyProvider::PLUGIN_STACK_PRE_PROCESS);
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface[]
     */
    protected function getPostProcessorStack()
    {
        return $this->getProvidedDependency(ApiDependencyProvider::PLUGIN_STACK_POST_PROCESS);
    }

}
