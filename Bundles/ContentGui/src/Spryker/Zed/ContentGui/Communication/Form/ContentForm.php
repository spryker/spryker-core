<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\ContentGui\Business\ContentGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentGui\ContentGuiConfig getConfig()
 */
class ContentForm extends AbstractType
{
    public const FIELD_NAME = 'name';
    public const FIELD_DESCRIPTION = 'description';
    public const FIELD_CONTENT_TERM_KEY = 'content_term_key';
    public const FIELD_CONTENT_TYPE_KEY = 'content_type_key';
    public const FIELD_LOCALES = 'localizedContents';

    public const LABEL_NAME = 'Name';
    public const LABEL_DESCRIPTION = 'Description';

    public const OPTION_AVAILABLE_LOCALES = 'OPTION_AVAILABLE_LOCALES';
    public const OPTION_CONTENT_ITEM_FORM_PLUGIN = 'OPTION_CONTENT_ITEM_FORM_PLUGIN';

    public const TYPE_DATA = 'data';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_AVAILABLE_LOCALES);
        $resolver->setRequired(static::OPTION_CONTENT_ITEM_FORM_PLUGIN);
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
            ->addNameField($builder)
            ->addDescriptionField($builder)
            ->addContentTermKey($builder)
            ->addContentTypeKey($builder)
            ->addLocaleCollection($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => static::LABEL_NAME,
            'constraints' => array_merge(
                $this->getFieldDefaultConstraints(),
                [
                    new Length(['max' => 255]),
                ]
            ),
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
        $builder->add(static::FIELD_DESCRIPTION, TextareaType::class, [
            'label' => static::LABEL_DESCRIPTION,
            'constraints' => array_merge(
                $this->getFieldDefaultConstraints(),
                [
                    new Length(['max' => 1024]),
                ]
            ),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addContentTermKey(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_CONTENT_TERM_KEY,
            HiddenType::class
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addContentTypeKey(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_CONTENT_TYPE_KEY,
            HiddenType::class
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addLocaleCollection(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_LOCALES, CollectionType::class, [
            'entry_type' => LocalizedContentForm::class,
            'entry_options' => [
                'label' => false,
                static::OPTION_CONTENT_ITEM_FORM_PLUGIN => $options[static::OPTION_CONTENT_ITEM_FORM_PLUGIN],
            ],
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getFieldDefaultConstraints(): array
    {
        return [
                new NotBlank(),
                new Required(),
            ];
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'content';
    }
}
