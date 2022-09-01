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
    /**
     * @var string
     */
    protected const NAME_VALIDATION_GROUP = 'name_validation_group';

    /**
     * @var string
     */
    protected const LABEL_META_TITLE = 'Title';

    /**
     * @var string
     */
    protected const LABEL_META_KEYWORDS = 'Keywords';

    /**
     * @var string
     */
    protected const LABEL_META_DESCRIPTION = 'Description';

    /**
     * @var string
     */
    protected const PLACEHOLDER_NAME = 'Provide a name';

    /**
     * @var string
     */
    protected const PLACEHOLDER_DESCRIPTION = 'Provide description';

    /**
     * @var string
     */
    protected const PLACEHOLDER_META_TITLE = 'Provide meta title';

    /**
     * @var string
     */
    protected const PLACEHOLDER_META_KEYWORDS = 'Provide meta keywords';

    /**
     * @var string
     */
    protected const PLACEHOLDER_META_DESCRIPTION = 'Provide meta description';

    /**
     * @var string
     */
    protected const VALIDATION_MESSAGE_NOT_BLANK = 'The value cannot be empty. Please fill in this input.';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<mixed> $options
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
            $this->getFactory()->createLocaleTransformer(),
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(LocalizedAttributesTransfer::NAME, TextType::class, [
            'attr' => [
                'placeholder' => static::PLACEHOLDER_NAME,
            ],
            'required' => false,
            'empty_data' => '',
            'constraints' => [
                new NotBlank([
                    'message' => static::VALIDATION_MESSAGE_NOT_BLANK,
                    'groups' => static::NAME_VALIDATION_GROUP,
                ]),
                new Length([
                    'max' => 255,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
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
            'empty_data' => '',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addMetaTitleField(FormBuilderInterface $builder)
    {
        $builder->add(LocalizedAttributesTransfer::META_TITLE, TextType::class, [
            'attr' => [
                'placeholder' => static::PLACEHOLDER_META_TITLE,
            ],
            'label' => static::LABEL_META_TITLE,
            'required' => false,
            'constraints' => [
                new Length([
                    'max' => 255,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addMetaKeywordsField(FormBuilderInterface $builder)
    {
        $builder->add(LocalizedAttributesTransfer::META_KEYWORDS, TextType::class, [
            'attr' => [
                'placeholder' => static::PLACEHOLDER_META_KEYWORDS,
            ],
            'label' => static::LABEL_META_KEYWORDS,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addMetaDescriptionField(FormBuilderInterface $builder)
    {
        $builder->add(LocalizedAttributesTransfer::META_DESCRIPTION, TextareaType::class, [
            'attr' => [
                'placeholder' => static::PLACEHOLDER_META_DESCRIPTION,
            ],
            'label' => static::LABEL_META_DESCRIPTION,
            'required' => false,
        ]);

        return $this;
    }
}
