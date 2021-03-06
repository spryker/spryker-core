<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
    protected const PLACEHOLDER_NAME = 'Provide a name';
    protected const PLACEHOLDER_DESCRIPTION = 'Provide description';
    protected const PLACEHOLDER_META_TITLE = 'Provide meta title';
    protected const PLACEHOLDER_META_KEYWORDS = 'Provide meta keywords';
    protected const PLACEHOLDER_META_DESCRIPTION = 'Provide meta description';

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
        $this->addLocaleHiddenField($builder)
            ->addNameField($builder)
            ->addDescriptionField($builder)
            ->addMetaTitleField($builder)
            ->addMetaKeywordsField($builder)
            ->addMetaDescriptionField($builder);
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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocaleHiddenField(FormBuilderInterface $builder)
    {
        $builder->add(LocalizedAttributesTransfer::LOCALE, HiddenType::class);
        $builder->get(LocalizedAttributesTransfer::LOCALE)->addModelTransformer(
            $this->getFactory()->createLocaleTransformer()
        );

        return $this;
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
        $builder->add(LocalizedAttributesTransfer::NAME, TextType::class, [
            'attr' => [
                'placeholder' => static::PLACEHOLDER_NAME,
            ],
            'required' => true,
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
            'attr' => [
                'placeholder' => static::PLACEHOLDER_DESCRIPTION,
            ],
            'required' => false,
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
    protected function addMetaTitleField(FormBuilderInterface $builder)
    {
        $builder->add(LocalizedAttributesTransfer::META_TITLE, TextType::class, [
            'attr' => [
                'placeholder' => static::PLACEHOLDER_META_TITLE,
            ],
            'required' => false,
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
    protected function addMetaKeywordsField(FormBuilderInterface $builder)
    {
        $builder->add(LocalizedAttributesTransfer::META_KEYWORDS, TextType::class, [
            'attr' => [
                'placeholder' => static::PLACEHOLDER_META_KEYWORDS,
            ],
            'required' => false,
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
    protected function addMetaDescriptionField(FormBuilderInterface $builder)
    {
        $builder->add(LocalizedAttributesTransfer::META_DESCRIPTION, TextareaType::class, [
            'attr' => [
                'placeholder' => static::PLACEHOLDER_META_DESCRIPTION,
            ],
            'required' => false,
        ]);

        return $this;
    }
}
