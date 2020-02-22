<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OrderCustomReference;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToPersistentCartClientInterface;
use Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToQuoteClientInterface;
use Spryker\Client\OrderCustomReference\Setter\OrderCustomReferenceSetter;
use Spryker\Client\OrderCustomReference\Setter\OrderCustomReferenceSetterInterface;
use Spryker\Client\OrderCustomReference\Validator\OrderCustomReferenceValidator;
use Spryker\Client\OrderCustomReference\Validator\OrderCustomReferenceValidatorInterface;

class OrderCustomReferenceFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\OrderCustomReference\Setter\OrderCustomReferenceSetterInterface
     */
    public function createOrderCustomReferenceSetter(): OrderCustomReferenceSetterInterface
    {
        return new OrderCustomReferenceSetter(
            $this->getPersistentCartClient(),
            $this->createOrderCustomReferenceValidator(),
            $this->getQuoteClient()
        );
    }

    /**
     * @return \Spryker\Client\OrderCustomReference\Validator\OrderCustomReferenceValidatorInterface
     */
    public function createOrderCustomReferenceValidator(): OrderCustomReferenceValidatorInterface
    {
        return new OrderCustomReferenceValidator();
    }

    /**
     * @return \Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToPersistentCartClientInterface
     */
    public function getPersistentCartClient(): OrderCustomReferenceToPersistentCartClientInterface
    {
        return $this->getProvidedDependency(OrderCustomReferenceDependencyProvider::CLIENT_PERSISTENT_CART);
    }

    /**
     * @return \Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToQuoteClientInterface
     */
    public function getQuoteClient(): OrderCustomReferenceToQuoteClientInterface
    {
        return $this->getProvidedDependency(OrderCustomReferenceDependencyProvider::CLIENT_QUOTE);
    }
}
