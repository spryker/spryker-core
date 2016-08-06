<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Spryker\Zed\ProductManagement\Communication\Form\AbstractSubForm;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class GeneralForm extends AbstractSubForm
{

    const FIELD_NAME = 'name';
    const FIELD_DESCRIPTION = 'description';

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
            ->addNameField($builder)
            ->addDescriptionField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_NAME, 'text', [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'groups' => [ProductFormAdd::VALIDATION_GROUP_GENERAL]
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9\.\-\_\s*]+$/',
                        'message' => 'Invalid value provided. Valid characters: "a-z A-Z", "0-9", ". - _"',
                        'groups' => [ProductFormAdd::VALIDATION_GROUP_GENERAL]
                    ]),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDescriptionField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_DESCRIPTION, 'textarea', [
                'required' => false,
                'constraints' => [
                    //new NotBlank(),
                ],
            ]);

        return $this;
    }

}
