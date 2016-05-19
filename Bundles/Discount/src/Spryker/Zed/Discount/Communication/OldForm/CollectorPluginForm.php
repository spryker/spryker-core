<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Required;

class CollectorPluginForm extends AbstractRuleForm
{

    const FIELD_ID_DISCOUNT_COLLECTOR = 'id_discount_collector';
    const FIELD_COLLECTOR_PLUGIN = 'collector_plugin';
    const FIELD_VALUE = 'value';
    const FIELD_REMOVE = 'remove';

    /**
     * @return string
     */
    public function getName()
    {
        return 'collector_plugin';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addIdDiscountCollectorField($builder)
            ->addCollectorPluginField($builder)
            ->addValueField($builder)
            ->addRemoveButton($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdDiscountCollectorField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_DISCOUNT_COLLECTOR, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCollectorPluginField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_COLLECTOR_PLUGIN, 'choice', [
            'label' => 'Collector Plugin',
            'multiple' => false,
            'choices' => $this->getAvailableCollectorPlugins(),
            'constraints' => [
                new Required(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_VALUE, 'text', [
            'label' => 'Value',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addRemoveButton(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_REMOVE, 'button', [
            'attr' => [
                'class' => 'btn btn-xs btn-danger remove-form-collection',
            ],
        ]);

        return $this;
    }

}
