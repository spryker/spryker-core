<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\ProductManagement;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig getConfig()
 */
class ServiceDateTimeEnabledProductConcreteFormExpanderPlugin extends AbstractPlugin implements ProductConcreteFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the `ProductConcreteForm` with a checkbox field for enabling/disabling service date time.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $dataProvider = $this->getFactory()->createServiceDateTimeEnabledProductConcreteFormDataProvider();
        $form = $this->getFactory()->createServiceDateTimeEnabledProductConcreteForm();

        $form->buildForm(
            $builder,
            $dataProvider->getOptions(),
        );

        return $builder;
    }
}
