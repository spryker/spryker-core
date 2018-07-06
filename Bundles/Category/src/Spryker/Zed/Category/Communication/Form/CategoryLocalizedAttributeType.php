<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Form;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 */
class CategoryLocalizedAttributeType extends AbstractType
{
    const OPTION_CATEGORY_QUERY_CONTAINER = 'category query container';

    const FIELD_NAME = 'name';
    const FIELD_FK_LOCALE = 'fk_locale';
    const FIELD_LOCALE_NAME = 'locale_name';
    const FIELD_META_TITLE = 'meta_title';
    const FIELD_META_DESCRIPTION = 'meta_description';
    const FIELD_META_KEYWORDS = 'meta_keywords';
    const FIELD_CATEGORY_IMAGE_NAME = 'category_image_name';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults(['data_class' => CategoryLocalizedAttributesTransfer::class])
            ->setRequired(static::OPTION_CATEGORY_QUERY_CONTAINER);
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
            ->addFkLocaleField($builder)
            ->addLocaleNameField($builder)
            ->addNameField($builder, $options[static::OPTION_CATEGORY_QUERY_CONTAINER])
            ->addMetaTitleField($builder)
            ->addMetaDescriptionField($builder)
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
            ->add(self::FIELD_FK_LOCALE, HiddenType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'property_path' => 'locale.idLocale',
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
            ->add(self::FIELD_LOCALE_NAME, HiddenType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'property_path' => 'locale.localeName',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder, CategoryQueryContainerInterface $categoryQueryContainer)
    {
        $builder
            ->add(self::FIELD_NAME, TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Callback([
                        'callback' => function ($key, ExecutionContextInterface $context) use ($categoryQueryContainer) {
                            $data = $context->getRoot()->getData();

                            $exists = false;
                            if ($data instanceof CategoryTransfer && $key) {
                                $exists = $categoryQueryContainer
                                    ->queryFirstLevelChildrenByName(
                                        $data->getParentCategoryNode()->getIdCategoryNode(),
                                        $key
                                    )
                                    ->count() > 0;
                            }

                            if ($exists) {
                                $context->addViolation(sprintf('Category with name "%s" already in use in this category level, please choose another one.', $key));
                            }
                        },
                    ]),
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
            ->add(self::FIELD_META_TITLE, TextType::class, [
                'label' => 'Meta Title',
                'required' => false,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMetaDescriptionField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_META_DESCRIPTION, TextareaType::class, [
                'label' => 'Meta Description',
                'required' => false,
                'attr' => [
                    'rows' => 5,
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
            ->add(self::FIELD_META_KEYWORDS, TextareaType::class, [
                'label' => 'Meta Keywords',
                'required' => false,
            ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'localizedAttributes';
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
