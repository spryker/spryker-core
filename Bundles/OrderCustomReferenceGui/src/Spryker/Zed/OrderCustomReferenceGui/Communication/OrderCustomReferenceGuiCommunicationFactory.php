<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReferenceGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\OrderCustomReferenceGui\Dependency\Facade\OrderCustomReferenceGuiToOrderCustomReferenceFacadeInterface;
use Spryker\Zed\OrderCustomReferenceGui\OrderCustomReferenceGuiDependencyProvider;

class OrderCustomReferenceGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\OrderCustomReferenceGui\Dependency\Facade\OrderCustomReferenceGuiToOrderCustomReferenceFacadeInterface
     */
    public function getOrderCustomReferenceFacade(): OrderCustomReferenceGuiToOrderCustomReferenceFacadeInterface
    {
        return $this->getProvidedDependency(OrderCustomReferenceGuiDependencyProvider::FACADE_ORDER_CUSTOM_REFERENCE);
    }
}
