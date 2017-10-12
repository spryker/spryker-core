<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Spryker\Zed\ProductManagement\Communication\Form\AbstractSubForm;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductNameRegex;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class GeneralForm extends AbstractSubForm
{
    const FIELD_NAME = 'name';
    const FIELD_DESCRIPTION = 'description';

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

        $this
            ->addNameField($builder, $options)
            ->addDescriptionField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_NAME, 'text', [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'groups' => [ProductFormAdd::VALIDATION_GROUP_GENERAL],
                    ]),

                    new ProductNameRegex([
                        'groups' => [ProductFormAdd::VALIDATION_GROUP_GENERAL],
                    ]),
                ],
                'attr' => [
                    'data-translation-key' => self::FIELD_NAME,
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addDescriptionField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::FIELD_DESCRIPTION, 'textarea', [
                'required' => false,
            ]);

        return $this;
    }
}
