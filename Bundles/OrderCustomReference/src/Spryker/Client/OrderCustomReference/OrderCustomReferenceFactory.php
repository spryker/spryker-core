<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OrderCustomReference;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToPersistentCartClientInterface;
use Spryker\Client\OrderCustomReference\Setter\OrderCustomReferenceSetter;
use Spryker\Client\OrderCustomReference\Setter\OrderCustomReferenceSetterInterface;

/**
 * @method \Spryker\Client\OrderCustomReference\OrderCustomReferenceConfig getConfig()
 */
class OrderCustomReferenceFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\OrderCustomReference\Setter\OrderCustomReferenceSetterInterface
     */
    public function createOrderCustomReferenceSetter(): OrderCustomReferenceSetterInterface
    {
        return new OrderCustomReferenceSetter(
            $this->getPersistentCartClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToPersistentCartClientInterface
     */
    public function getPersistentCartClient(): OrderCustomReferenceToPersistentCartClientInterface
    {
        return $this->getProvidedDependency(OrderCustomReferenceDependencyProvider::CLIENT_PERSISTENT_CART);
    }
}
