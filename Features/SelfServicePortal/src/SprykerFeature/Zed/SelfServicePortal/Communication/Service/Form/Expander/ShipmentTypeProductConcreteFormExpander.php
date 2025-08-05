<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\Expander;

use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider\ShipmentTypeProductConcreteFormDataProvider;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;

class ShipmentTypeProductConcreteFormExpander implements ShipmentTypeProductConcreteFormExpanderInterface
{
    public function __construct(
        protected ShipmentTypeProductConcreteFormDataProvider $dataProvider,
        protected FormTypeInterface $form
    ) {
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this->form->buildForm(
            $builder,
            $this->dataProvider->getOptions(),
        );

        return $builder;
    }
}
