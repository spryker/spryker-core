<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuoteRequest\Converter\QuoteRequestConverter;
use Spryker\Client\QuoteRequest\Converter\QuoteRequestConverterInterface;
use Spryker\Client\QuoteRequest\Creator\QuoteRequestCreator;
use Spryker\Client\QuoteRequest\Creator\QuoteRequestCreatorInterface;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToCompanyUserClientInterface;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToPersistentCartClientInterface;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToQuoteClientInterface;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToZedRequestClientInterface;
use Spryker\Client\QuoteRequest\Reader\QuoteRequestReader;
use Spryker\Client\QuoteRequest\Reader\QuoteRequestReaderInterface;
use Spryker\Client\QuoteRequest\Status\QuoteRequestStatus;
use Spryker\Client\QuoteRequest\Status\QuoteRequestStatusInterface;
use Spryker\Client\QuoteRequest\Validator\QuoteValidator;
use Spryker\Client\QuoteRequest\Validator\QuoteValidatorInterface;
use Spryker\Client\QuoteRequest\Zed\QuoteRequestStub;
use Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface;

/**
 * @method \Spryker\Client\QuoteRequest\QuoteRequestConfig getConfig()
 */
class QuoteRequestFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\QuoteRequest\Converter\QuoteRequestConverterInterface
     */
    public function createQuoteRequestConverter(): QuoteRequestConverterInterface
    {
        return new QuoteRequestConverter(
            $this->getPersistentCartClient(),
            $this->getQuoteClient(),
            $this->createQuoteRequestStatus()
        );
    }

    /**
     * @return \Spryker\Client\QuoteRequest\Validator\QuoteValidatorInterface
     */
    public function createQuoteValidator(): QuoteValidatorInterface
    {
        return new QuoteValidator(
            $this->getCompanyUserClient(),
            $this->getQuoteRequestCreatePreCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Client\QuoteRequest\Creator\QuoteRequestCreatorInterface
     */
    public function createQuoteRequestCreator(): QuoteRequestCreatorInterface
    {
        return new QuoteRequestCreator(
            $this->createQuoteRequestStub(),
            $this->createQuoteValidator()
        );
    }

    /**
     * @return \Spryker\Client\QuoteRequest\Status\QuoteRequestStatusInterface
     */
    public function createQuoteRequestStatus(): QuoteRequestStatusInterface
    {
        return new QuoteRequestStatus($this->getConfig());
    }

    /**
     * @return \Spryker\Client\QuoteRequest\Reader\QuoteRequestReaderInterface
     */
    public function createQuoteRequestReader(): QuoteRequestReaderInterface
    {
        return new QuoteRequestReader($this->createQuoteRequestStub());
    }

    /**
     * @return \Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface
     */
    public function createQuoteRequestStub(): QuoteRequestStubInterface
    {
        return new QuoteRequestStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToZedRequestClientInterface
     */
    public function getZedRequestClient(): QuoteRequestToZedRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToPersistentCartClientInterface
     */
    public function getPersistentCartClient(): QuoteRequestToPersistentCartClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::CLIENT_PERSISTENT_CART);
    }

    /**
     * @return \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToQuoteClientInterface
     */
    public function getQuoteClient(): QuoteRequestToQuoteClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): QuoteRequestToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \Spryker\Client\QuoteRequestExtension\Dependency\Plugin\QuoteRequestCreatePreCheckPluginInterface[]
     */
    public function getQuoteRequestCreatePreCheckPlugins(): array
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::PLUGINS_QUOTE_REQUEST_CREATE_PRE_CHECK);
    }
}
