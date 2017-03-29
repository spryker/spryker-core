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
            $this->getPreProcessStack(),
            $this->getPostProcessStack(),
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
     * @return \Spryker\Service\UtilEncoding\UtilEncodingService
     */
    protected function getUtilEncoding()
    {
        return $this->getProvidedDependency(ApiDependencyProvider::SERVICE_ENCODING);
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface[]
     */
    protected function getPreProcessStack()
    {
        return $this->getConfig()->getPreProcessorStack();
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface[]
     */
    protected function getPostProcessStack()
    {
        return $this->getConfig()->getPostProcessorStack();
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Spryker\Zed\Api\Business\Model\Transformer
     */
    public function createTransformer(ApiRequestTransfer $apiRequestTransfer)
    {
        return new Transformer($this->createFormatter($apiRequestTransfer->getFormatType()));
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
     * Implement in your BundleApi BundleApiDepdenencyProvider.
     *
     * @return array
     */
    protected function getValidatorPluginStack()
    {
        return [];
    }

}
