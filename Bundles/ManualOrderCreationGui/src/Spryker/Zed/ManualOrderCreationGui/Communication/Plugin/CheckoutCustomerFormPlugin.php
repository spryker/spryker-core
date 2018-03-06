<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderCreationGui\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ManualOrderCreationGui\Communication\ManualOrderCreationGuiCommunicationFactory getFactory()
 */
class CheckoutCustomerFormPlugin extends AbstractPlugin implements CheckoutFormPluginInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $formType = $this->getFactory()
            ->createCustomerType();

        $dataProvider = $this->getFactory()
            ->createCustomerDataProvider();

        $manualOrderEntryTransfer = $builder->getData();
        $dataProvider->getData($manualOrderEntryTransfer);

        $formType->buildForm(
            $builder,
            $dataProvider->getOptions()
        );
    }
}
