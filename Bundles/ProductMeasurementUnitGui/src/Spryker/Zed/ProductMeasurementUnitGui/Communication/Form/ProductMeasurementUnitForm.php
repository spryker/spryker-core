<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitGui\Communication\Form;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitGui\Communication\ProductMeasurementUnitGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMeasurementUnitGui\ProductMeasurementUnitGuiConfig getConfig()
 */
class ProductMeasurementUnitForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_CODE = 'code';

    /**
     * @var string
     */
    protected const FIELD_NAME = 'name';

    /**
     * @var string
     */
    protected const FIELD_DEFAULT_PRECISION = 'default_precision';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addFieldCode($builder, $options)
            ->addFieldName($builder)
            ->addFieldDefaultPrecision($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ProductMeasurementUnitTransfer::class,
            'is_edit' => false,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addFieldCode(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_CODE, TextType::class, [
            'label' => 'Code (unique)',
            'required' => true,
            'disabled' => $options['is_edit'] ?? false,
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFieldName(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'Name',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFieldDefaultPrecision(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DEFAULT_PRECISION, IntegerType::class, [
            'label' => 'Default Precision',
            'required' => true,
            'attr' => ['min' => 1],
            'constraints' => [
                new NotBlank(),
                new GreaterThanOrEqual(1),
            ],
        ]);

        return $this;
    }
}
