<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout;

use Spryker\Client\Checkout\Dependency\Client\CheckoutToGlossaryStorageClientInterface;
use Spryker\Client\Checkout\Dependency\Client\CheckoutToLocaleClientInterface;
use Spryker\Client\Checkout\ErrorMessage\ErrorMessageTranslator;
use Spryker\Client\Checkout\ErrorMessage\ErrorMessageTranslatorInterface;
use Spryker\Client\Checkout\Zed\CheckoutStub;
use Spryker\Client\Kernel\AbstractFactory;

class CheckoutFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Checkout\Zed\CheckoutStubInterface
     */
    public function createZedStub()
    {
        return new CheckoutStub($this->getProvidedDependency(CheckoutDependencyProvider::SERVICE_ZED));
    }

    /**
     * @return \Spryker\Client\Checkout\ErrorMessage\ErrorMessageTranslatorInterface
     */
    public function createErrorMessageTranslator(): ErrorMessageTranslatorInterface
    {
        return new ErrorMessageTranslator(
            $this->getGlossaryStorageClient(),
            $this->getLocaleClient()
        );
    }

    /**
     * @return \Spryker\Client\Checkout\Dependency\Client\CheckoutToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): CheckoutToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \Spryker\Client\Checkout\Dependency\Client\CheckoutToLocaleClientInterface
     */
    public function getLocaleClient(): CheckoutToLocaleClientInterface
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::CLIENT_LOCALE);
    }
}
