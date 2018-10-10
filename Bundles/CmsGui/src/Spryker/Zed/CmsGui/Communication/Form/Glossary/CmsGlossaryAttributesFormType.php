<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Glossary;

use Spryker\Zed\CmsGui\Communication\Form\ArrayObjectTransformerTrait;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class CmsGlossaryAttributesFormType extends AbstractType
{
    public const FIELD_PLACEHOLDER = 'placeholder';
    public const FIELD_FK_PAGE = 'fkPage';
    public const FIELD_FK_GLOSSARY_MAPPING = 'fkCmsGlossaryMapping';
    public const FIELD_TEMPLATE_NAME = 'templateName';
    public const FIELD_SEARCH_OPTION = 'searchOption';
    public const FIELD_TRANSLATIONS = 'translations';
    public const FIELD_TRANSLATION_KEY = 'translationKey';

    public const GROUP_PLACEHOLDER_CHECK = 'placeholder_check';

    public const OPTION_GLOSSARY_KEY_SEARCH_OPTIONS = 'glossaryKeySearchOptions';

    use ArrayObjectTransformerTrait;

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $defaultData = $form->getConfig()->getData();

                if (!isset($defaultData[static::FIELD_FK_GLOSSARY_MAPPING])) {
                    return [Constraint::DEFAULT_GROUP, static::GROUP_PLACEHOLDER_CHECK];
                }

                return [Constraint::DEFAULT_GROUP];
            },
        ]);
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
            ->addFkPageField($builder)
            ->addIdCmsGlossaryKeyMappingField($builder)
            ->addTemplateNameField($builder)
            ->addPlaceholderField($builder)
            ->addTranslationsField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TRANSLATION_KEY, TextType::class, [
            'constraints' => [
                $this->getFactory()->createUniqueGlossaryForSearchTypeConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkPageField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_PAGE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPlaceholderField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PLACEHOLDER, TextType::class, [
            'label' => 'Placeholder',
            'disabled' => 'disabled',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCmsGlossaryKeyMappingField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_GLOSSARY_MAPPING, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTemplateNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TEMPLATE_NAME, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSearchOptionField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SEARCH_OPTION, ChoiceType::class, [
            'label' => 'Search Type',
            'choices' => $options[static::OPTION_GLOSSARY_KEY_SEARCH_OPTIONS],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTranslationsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TRANSLATIONS, CollectionType::class, [
            'entry_type' => CmsGlossaryTranslationFormType::class,
            'allow_add' => true,
        ]);

        $builder->get(static::FIELD_TRANSLATIONS)
            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

    /**
     * @return array
     */
    protected function getPlaceholderConstants()
    {
        $placeholderConstraints = [
            new Required(),
            new NotBlank(),
            new Length(['max' => 255]),
        ];

        $placeholderConstraints[] = new Callback([
            'callback' => function ($placeholder, ExecutionContextInterface $context) {
                $formData = $context->getRoot()->getViewData();
                if ($this->getFactory()->getCmsFacade()->hasPagePlaceholderMapping($formData[static::FIELD_FK_PAGE], $placeholder)) {
                    $context->addViolation('Placeholder has already mapped.');
                }
            },
            'groups' => [static::GROUP_PLACEHOLDER_CHECK],
        ]);

        return $placeholderConstraints;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'cms_glossary_attribute';
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
