<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 */
class ContentForm extends AbstractType
{
    public const FIELD_NAME = 'name';
    public const FIELD_DESCRIPTION = 'description';
    public const FIELD_CONTENT_TERM_CANDIDATE_KEY = 'content_term_candidate_key';
    public const FIELD_CONTENT_TYPE_CANDIDATE_KEY = 'content_type_candidate_key';
    public const FIELD_LOCALES = 'localizedContents';

    public const OPTION_AVAILABLE_LOCALES = 'OPTION_AVAILABLE_LOCALES';
    public const OPTION_CONTENT_ITEM_TERM_FORM = 'OPTION_CONTENT_ITEM_TERM_FORM';
    public const OPTION_CONTENT_ITEM_TRANSFORM = 'OPTION_CONTENT_ITEM_TRANSFORM';
    public const OPTION_CONTENT_ITEM_REVERS_TRANSFORM = 'OPTION_CONTENT_ITEM_REVERS_TRANSFORM';

    public const TYPE_DATA = 'data';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_AVAILABLE_LOCALES);
        $resolver->setRequired(static::OPTION_CONTENT_ITEM_TERM_FORM);
        $resolver->setRequired(static::OPTION_CONTENT_ITEM_TRANSFORM);
        $resolver->setRequired(static::OPTION_CONTENT_ITEM_REVERS_TRANSFORM);
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
            ->addNameField($builder)
            ->addDescriptionField($builder)
            ->addContentTermCandidateKey($builder)
            ->addContentTypeCandidateKey($builder)
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
            'attr' => [
                'placeholder' => 'Name',
            ],
            'label' => false,
            'constraints' => $this->getFieldDefaultConstraints(),
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
        $builder->add(static::FIELD_DESCRIPTION, TextType::class, [
            'attr' => [
                'placeholder' => 'Description',
            ],
            'label' => false,
            'constraints' => $this->getFieldDefaultConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addContentTermCandidateKey(FormBuilderInterface $builder): self
    {
        $builder->add(
            static::FIELD_CONTENT_TERM_CANDIDATE_KEY,
            HiddenType::class
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addContentTypeCandidateKey(FormBuilderInterface $builder): self
    {
        $builder->add(
            static::FIELD_CONTENT_TYPE_CANDIDATE_KEY,
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
                'attr' => [
                    'rows' => 10,
                ],
                static::OPTION_CONTENT_ITEM_TERM_FORM => $options[static::OPTION_CONTENT_ITEM_TERM_FORM],
                static::OPTION_CONTENT_ITEM_TRANSFORM => $options[static::OPTION_CONTENT_ITEM_TRANSFORM],
                static::OPTION_CONTENT_ITEM_REVERS_TRANSFORM => $options[static::OPTION_CONTENT_ITEM_REVERS_TRANSFORM],
            ],
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getFieldDefaultConstraints()
    {
        return [
            new NotBlank(),
            new Required(),
        ];
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'content';
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
