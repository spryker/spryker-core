<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReferenceGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\OrderCustomReferenceGui\Communication\Form\OrderCustomReferenceForm;
use Spryker\Zed\OrderCustomReferenceGui\Dependency\Facade\OrderCustomReferenceGuiToOrderCustomReferenceFacadeInterface;
use Spryker\Zed\OrderCustomReferenceGui\OrderCustomReferenceGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

class OrderCustomReferenceGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\OrderCustomReferenceGui\Dependency\Facade\OrderCustomReferenceGuiToOrderCustomReferenceFacadeInterface
     */
    public function getOrderCustomReferenceFacade(): OrderCustomReferenceGuiToOrderCustomReferenceFacadeInterface
    {
        return $this->getProvidedDependency(OrderCustomReferenceGuiDependencyProvider::FACADE_ORDER_CUSTOM_REFERENCE);
    }

    /**
     * @param array $data
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getOrderCustomReferenceForm(array $data = []): FormInterface
    {
        return $this->getFormFactory()->create(OrderCustomReferenceForm::class, $data);
    }
}
