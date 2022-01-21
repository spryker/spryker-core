<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Canceller\QuoteRequestCanceller;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Canceller\QuoteRequestCancellerInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Creator\QuoteRequestCreator;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Creator\QuoteRequestCreatorInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapper;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Reader\QuoteRequestReader;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Reader\QuoteRequestReaderInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilder;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Reviser\QuoteRequestReviser;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Reviser\QuoteRequestReviserInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Sender\QuoteRequestSender;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Sender\QuoteRequestSenderInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Updater\QuoteRequestUpdater;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Updater\QuoteRequestUpdaterInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Validator\QuoteRequestValidator;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Validator\QuoteRequestValidatorInterface;

/**
 * @method \Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiClientInterface getClient()
 * @method \Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig getConfig()
 */
class QuoteRequestsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface
     */
    public function createQuoteRequestMapper(): QuoteRequestMapperInterface
    {
        return new QuoteRequestMapper(
            $this->getRestQuoteRequestAttributesExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Canceller\QuoteRequestCancellerInterface
     */
    public function createQuoteRequestCanceller(): QuoteRequestCancellerInterface
    {
        return new QuoteRequestCanceller(
            $this->getQuoteRequestClient(),
            $this->createQuoteRequestRestResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Reader\QuoteRequestReaderInterface
     */
    public function createQuoteRequestReader(): QuoteRequestReaderInterface
    {
        return new QuoteRequestReader(
            $this->getQuoteRequestClient(),
            $this->createQuoteRequestRestResponseBuilder(),
            $this->createQuoteRequestMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface
     */
    public function createQuoteRequestRestResponseBuilder(): QuoteRequestRestResponseBuilderInterface
    {
        return new QuoteRequestRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createQuoteRequestMapper(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Creator\QuoteRequestCreatorInterface
     */
    public function createQuoteRequestCreator(): QuoteRequestCreatorInterface
    {
        return new QuoteRequestCreator(
            $this->getClient(),
            $this->createQuoteRequestRestResponseBuilder(),
            $this->createQuoteRequestMapper(),
            $this->createQuoteRequestValidator(),
        );
    }

    /**
     * @return array<\Spryker\Glue\QuoteRequestsRestApiExtension\Dependency\Plugin\RestQuoteRequestAttributesExpanderPluginInterface>
     */
    public function getRestQuoteRequestAttributesExpanderPlugins(): array
    {
        return $this->getProvidedDependency(QuoteRequestsRestApiDependencyProvider::PLUGINS_REST_QUOTE_REQUEST_ATTRIBUTES_EXPANDER);
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface
     */
    public function getQuoteRequestClient(): QuoteRequestsRestApiToQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestsRestApiDependencyProvider::CLIENT_QUOTE_REQUEST);
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Updater\QuoteRequestUpdaterInterface
     */
    public function createQuoteRequestUpdater(): QuoteRequestUpdaterInterface
    {
        return new QuoteRequestUpdater(
            $this->getQuoteRequestClient(),
            $this->createQuoteRequestRestResponseBuilder(),
            $this->createQuoteRequestMapper(),
            $this->getClient(),
            $this->createQuoteRequestValidator(),
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Validator\QuoteRequestValidatorInterface
     */
    public function createQuoteRequestValidator(): QuoteRequestValidatorInterface
    {
        return new QuoteRequestValidator();
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Reviser\QuoteRequestReviserInterface
     */
    public function createQuoteRequestReviser(): QuoteRequestReviserInterface
    {
        return new QuoteRequestReviser(
            $this->createQuoteRequestRestResponseBuilder(),
            $this->getQuoteRequestClient(),
        );
    }

    /**
     * @return \Spryker\Glue\QuoteRequestsRestApi\Processor\Sender\QuoteRequestSenderInterface
     */
    public function createQuoteRequestSender(): QuoteRequestSenderInterface
    {
        return new QuoteRequestSender(
            $this->createQuoteRequestRestResponseBuilder(),
            $this->getQuoteRequestClient(),
        );
    }
}
