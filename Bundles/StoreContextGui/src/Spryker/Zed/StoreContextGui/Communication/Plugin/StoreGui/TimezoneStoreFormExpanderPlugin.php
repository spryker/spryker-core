<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\StoreContextGui\StoreContextGuiConfig getConfig()
 * @method \Spryker\Zed\StoreContextGui\Communication\StoreContextGuiCommunicationFactory getFactory()
 */
class TimezoneStoreFormExpanderPlugin extends AbstractPlugin implements StoreFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface>
     */
    public function expand(FormBuilderInterface $builder, StoreTransfer $storeTransfer): FormBuilderInterface
    {
        $formType = $this->getFactory()
            ->createStoreTimezoneForm();

        $dataProvider = $this->getFactory()
            ->createStoreTimezoneFormDataProvider();

        $formType->buildForm(
            $builder,
            $dataProvider->getOptions(),
        );

        return $builder;
    }
}
