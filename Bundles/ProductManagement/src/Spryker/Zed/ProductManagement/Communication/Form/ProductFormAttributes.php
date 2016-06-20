<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductFormAttributes extends AbstractType
{

    const FIELD_VALUE = 'value';

    /**
     * @var array
     */
    protected $attributes;

    /**
     * ProductFormAttributes constructor.
     */
    public function __construct(array $attributes=[])
    {
        $this->attributes = $attributes;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'productAttributes';
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
            ->addValueField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_VALUE, new Select2ComboBoxType(), [
            'label' => $builder->getName(),
            'choices' => $this->attributes[$builder->getName()],
            'multiple' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

}
