<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Form;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductPackagingUnitGui\Business\ProductPackagingUnitGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnitGui\Communication\ProductPackagingUnitGuiCommunicationFactory getFactory()
 */
class ProductPackagingUnitTypeTranslationFormType extends AbstractType
{
    const FIELD_TRANSLATION = 'translation';
    const FIELD_LOCALE_CODE = 'localeCode';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addTranslationField($builder)
            ->addLocalCode($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductPackagingUnitTypeTranslationTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTranslationField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TRANSLATION, TextType::class, [
            'label' => 'Name: *',
            'required' => true,
            'constraints' => [
                new NotBlank(),
             ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocalCode(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_LOCALE_CODE,
            HiddenType::class
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'product_packaging_unit_type_value_translation';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
