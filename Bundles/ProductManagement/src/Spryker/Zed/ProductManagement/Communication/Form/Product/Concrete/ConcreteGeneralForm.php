<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete;

use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Symfony\Component\Form\FormBuilderInterface;

class ConcreteGeneralForm extends GeneralForm
{
    const FIELD_IS_SEARCHABLE = 'is_searchable';

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
}
