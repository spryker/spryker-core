<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryGui\CategoryGuiConfig getConfig()
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 */
class CategoryLocalizedAttributeType extends AbstractType
{
    public const OPTION_META_DESCRIPTION_ROWS = 'meta_description_rows';

    protected const OPTION_DATA_CLASS = 'data_class';
    protected const OPTION_PROPERTY_PATH_LOCALE_ID_LOCALE = 'locale.idLocale';
    protected const OPTION_PROPERTY_PATH_LOCALE_LOCALE_NAME = 'locale.localeName';

    protected const FIELD_NAME = 'name';
    protected const FIELD_FK_LOCALE = 'fk_locale';
    protected const FIELD_LOCALE_NAME = 'locale_name';
    protected const FIELD_META_TITLE = 'meta_title';
    protected const FIELD_META_DESCRIPTION = 'meta_description';
    protected const FIELD_META_KEYWORDS = 'meta_keywords';
    protected const FIELD_CATEGORY_IMAGE_NAME = 'category_image_name';

    protected const LABEL_META_TITLE = 'Meta Title';
    protected const LABEL_META_DESCRIPTION = 'Meta Description';
    protected const LABEL_META_KEYWORDS = 'Meta Keywords';

    protected const BLOCK_PREFIX = 'localizedAttributes';

    protected const DEFAULT_META_DESCRIPTION_ROWS_NUMBER = 5;

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
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
            static::OPTION_DATA_CLASS => CategoryLocalizedAttributesTransfer::class,
            static::OPTION_META_DESCRIPTION_ROWS => static::DEFAULT_META_DESCRIPTION_ROWS_NUMBER,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addFkLocaleField($builder)
            ->addLocaleNameField($builder)
            ->addNameField($builder)
            ->addMetaTitleField($builder)
            ->addMetaDescriptionField($builder, $options)
            ->addMetaKeywordsField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkLocaleField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_FK_LOCALE, HiddenType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'property_path' => static::OPTION_PROPERTY_PATH_LOCALE_ID_LOCALE,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocaleNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_LOCALE_NAME, HiddenType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'property_path' => static::OPTION_PROPERTY_PATH_LOCALE_LOCALE_NAME,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_NAME, TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    $this->getFactory()->createCategoryLocalizedAttributeNameUniqueConstraint(),
                ],
                'required' => false,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMetaTitleField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_META_TITLE, TextType::class, [
                'label' => static::LABEL_META_TITLE,
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
    protected function addMetaDescriptionField(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(static::FIELD_META_DESCRIPTION, TextareaType::class, [
                'label' => static::LABEL_META_DESCRIPTION,
                'required' => false,
                'attr' => [
                    'rows' => $options[static::OPTION_META_DESCRIPTION_ROWS],
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMetaKeywordsField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_META_KEYWORDS, TextareaType::class, [
                'label' => static::LABEL_META_KEYWORDS,
                'required' => false,
            ]);

        return $this;
    }
}
