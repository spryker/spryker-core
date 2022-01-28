<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication;

use Spryker\Zed\Api\ApiDependencyProvider;
use Spryker\Zed\Api\Communication\EventListener\ApiControllerEventListener;
use Spryker\Zed\Api\Communication\EventListener\ApiControllerEventListenerInterface;
use Spryker\Zed\Api\Communication\Formatter\FormatterInterface;
use Spryker\Zed\Api\Communication\Formatter\JsonFormatter;
use Spryker\Zed\Api\Communication\Resolver\FormatterResolver;
use Spryker\Zed\Api\Communication\Resolver\FormatterResolverInterface;
use Spryker\Zed\Api\Communication\Transformer\Transformer;
use Spryker\Zed\Api\Communication\Transformer\TransformerInterface;
use Spryker\Zed\Api\Dependency\Service\ApiToUtilEncodingServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 * @method \Spryker\Zed\Api\Business\ApiFacadeInterface getFacade()
 */
class ApiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Api\Communication\EventListener\ApiControllerEventListenerInterface
     */
    public function createApiControllerEventListener(): ApiControllerEventListenerInterface
    {
        return new ApiControllerEventListener(
            $this->createTransformer(),
            $this->getFacade(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Zed\Api\Communication\Transformer\TransformerInterface
     */
    public function createTransformer(): TransformerInterface
    {
        return new Transformer(
            $this->createFormatterResolver(),
            $this->getConfig(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Zed\Api\Communication\Resolver\FormatterResolverInterface
     */
    public function createFormatterResolver(): FormatterResolverInterface
    {
        return new FormatterResolver([
            FormatterResolver::FORMATTER_TYPE_JSON => function () {
                return $this->createJsonFormatter();
            },
        ]);
    }

    /**
     * @return \Spryker\Zed\Api\Communication\Formatter\FormatterInterface
     */
    public function createJsonFormatter(): FormatterInterface
    {
        return new JsonFormatter($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\Api\Dependency\Service\ApiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ApiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ApiDependencyProvider::SERVICE_ENCODING);
    }
}
