<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\Cms\Business\CmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Cms\Persistence\CmsRepositoryInterface getRepository()
 * @method \Spryker\Zed\Cms\CmsConfig getConfig()
 */
class CmsGlossaryForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_FK_PAGE = 'fkPage';

    /**
     * @var string
     */
    public const FIELD_PLACEHOLDER = 'placeholder';

    /**
     * @var string
     */
    public const FIELD_GLOSSARY_KEY = 'glossary_key';

    /**
     * @var string
     */
    public const FIELD_ID_KEY_MAPPING = 'idCmsGlossaryKeyMapping';

    /**
     * @var string
     */
    public const FIELD_TEMPLATE_NAME = 'templateName';

    /**
     * @var string
     */
    public const FIELD_SEARCH_OPTION = 'search_option';

    /**
     * @var string
     */
    public const FIELD_TRANSLATION = 'translation';

    /**
     * @var string
     */
    protected const TYPE_GLOSSARY_NEW = 'New glossary';

    /**
     * @var string
     */
    protected const TYPE_GLOSSARY_FIND = 'Find glossary';

    /**
     * @var string
     */
    protected const TYPE_AUTO_GLOSSARY = 'Auto';

    /**
     * @var string
     */
    protected const TYPE_FULLTEXT_SEARCH = 'Full text';

    /**
     * @var string
     */
    protected const GROUP_PLACEHOLDER_CHECK = 'placeholder_check';

    /**
     * @var string
     */
    public const FIELD_FK_LOCALE = 'fk_locale';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $defaultData = $form->getConfig()->getData();

                if (!isset($defaultData[self::FIELD_ID_KEY_MAPPING])) {
                    return [Constraint::DEFAULT_GROUP, self::GROUP_PLACEHOLDER_CHECK];
                }

                return [Constraint::DEFAULT_GROUP];
            },
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addFkPageField($builder)
            ->addIdCmsGlossaryKeyMappingField($builder)
            ->addTemplateNameField($builder)
            ->addPlaceholderField($builder)
            ->addSearchOptionField($builder)
            ->addGlossaryKeyField($builder)
            ->addLocaleField($builder)
            ->addTranslationField($builder);
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
            'constraints' => $this->getPlaceholderConstants(),
            'disabled' => 'disabled',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_GLOSSARY_KEY, TextType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCmsGlossaryKeyMappingField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_KEY_MAPPING, HiddenType::class);

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
     *
     * @return $this
     */
    protected function addSearchOptionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SEARCH_OPTION, ChoiceType::class, [
            'label' => 'Search Type',
            'choices' => [
                static::TYPE_AUTO_GLOSSARY,
                static::TYPE_GLOSSARY_NEW,
                static::TYPE_GLOSSARY_FIND,
                static::TYPE_FULLTEXT_SEARCH,
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addLocaleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_LOCALE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTranslationField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TRANSLATION, TextareaType::class, [
            'label' => 'Content',
            'constraints' => [
                new NotBlank(),
            ],
            'attr' => [
                'class' => 'html-editor',
            ],
            'sanitize_xss' => true,
            'allowed_attributes' => ['style'],
            'allowed_html_tags' => ['iframe'],
        ]);

        return $this;
    }

    /**
     * @return array
     */
    protected function getPlaceholderConstants(): array
    {
        $placeholderConstraints = [
            new NotBlank(),
            new Length(['max' => 255]),
        ];

        $placeholderConstraints[] = new Callback([
            'callback' => function ($placeholder, ExecutionContextInterface $context) {
                $formData = $context->getRoot()->getViewData();
                if ($this->getFacade()->hasPagePlaceholderMapping($formData[self::FIELD_FK_PAGE], $placeholder)) {
                    $context->addViolation('Placeholder has already mapped');
                }
            },
            'groups' => [static::GROUP_PLACEHOLDER_CHECK],
        ]);

        return $placeholderConstraints;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'cms_glossary';
    }
}
