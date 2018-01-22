<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete;

use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;

class ConcreteGeneralForm extends GeneralForm
{
    const FIELD_IS_SEARCHABLE = 'is_searchable';
    const FIELD_VALID_FROM = 'valid_from';
    const FIELD_VALID_UNTIL = 'valid_until';

    /**
     * @return string
     */
    public function getName()
    {
        return 'product_general';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this->addIsSearchableField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIsSearchableField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_IS_SEARCHABLE, 'checkbox', [
                'label' => 'Searchable',
                'required' => false,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addValidFromField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_VALID_FROM, TimeType::class, [
                'label' => 'Valid From',
                'required' => false,
                'input'  => 'datetime',
                'widget' => 'choice',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addValidUntilField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_VALID_UNTIL, TimeType::class, [
                'label' => 'Valid Until',
                'required' => false,
                'input'  => 'datetime',
                'widget' => 'choice',
            ]);

        return $this;
    }
}
