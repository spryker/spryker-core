<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 */
class ProductLocalizedAttributesForm extends AbstractType
{
    protected const LABEL_NAME = 'Name';
    protected const LABEL_DESCRIPTION = 'Description';

    protected const PLACEHOLDER_NAME = 'Provide a name';
    protected const PLACEHOLDER_DESCRIPTION = 'Provide description';

    protected const VALIDATION_MESSAGE_NOT_BLANK = 'The value cannot be empty. Please fill in this input.';

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNameField($builder)
            ->addDescriptionField($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => false,
        ]);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(LocalizedAttributesTransfer::NAME, TextareaType::class, [
            'label' => static::LABEL_NAME,
            'attr' => [
                'placeholder' => static::PLACEHOLDER_NAME,
            ],
            'required' => false,
            'constraints' => [
                new NotBlank([
                    'message' => static::VALIDATION_MESSAGE_NOT_BLANK,
                ]),
                new Length([
                    'max' => 255,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDescriptionField(FormBuilderInterface $builder)
    {
        $builder->add(LocalizedAttributesTransfer::DESCRIPTION, TextareaType::class, [
            'label' => static::LABEL_DESCRIPTION,
            'attr' => [
                'placeholder' => static::PLACEHOLDER_DESCRIPTION,
            ],
            'required' => false,
        ]);

        return $this;
    }
}
