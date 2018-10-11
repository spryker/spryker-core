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
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\Cms\Business\CmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface getQueryContainer()
 */
class CmsGlossaryForm extends AbstractType
{
    public const FIELD_FK_PAGE = 'fkPage';
    public const FIELD_PLACEHOLDER = 'placeholder';
    public const FIELD_GLOSSARY_KEY = 'glossary_key';
    public const FIELD_ID_KEY_MAPPING = 'idCmsGlossaryKeyMapping';
    public const FIELD_TEMPLATE_NAME = 'templateName';
    public const FIELD_SEARCH_OPTION = 'search_option';
    public const FIELD_TRANSLATION = 'translation';

    public const TYPE_GLOSSARY_NEW = 'New glossary';
    public const TYPE_GLOSSARY_FIND = 'Find glossary';
    public const TYPE_AUTO_GLOSSARY = 'Auto';
    public const TYPE_FULLTEXT_SEARCH = 'Full text';

    public const GROUP_PLACEHOLDER_CHECK = 'placeholder_check';
    public const FIELD_FK_LOCALE = 'fk_locale';

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

                if (!isset($defaultData[self::FIELD_ID_KEY_MAPPING])) {
                    return [Constraint::DEFAULT_GROUP, self::GROUP_PLACEHOLDER_CHECK];
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
        $builder->add(self::FIELD_FK_PAGE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPlaceholderField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_PLACEHOLDER, TextType::class, [
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
        $builder->add(self::FIELD_GLOSSARY_KEY, TextType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCmsGlossaryKeyMappingField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_KEY_MAPPING, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTemplateNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_TEMPLATE_NAME, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSearchOptionField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_SEARCH_OPTION, ChoiceType::class, [
            'label' => 'Search Type',
            'choices' => [
                self::TYPE_AUTO_GLOSSARY,
                self::TYPE_GLOSSARY_NEW,
                self::TYPE_GLOSSARY_FIND,
                self::TYPE_FULLTEXT_SEARCH,
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
        $builder->add(self::FIELD_FK_LOCALE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTranslationField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_TRANSLATION, TextareaType::class, [
            'label' => 'Content',
            'constraints' => [
                new Required(),
                new NotBlank(),
            ],
            'attr' => [
                'class' => 'html-editor',
            ],
        ]);

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
                if ($this->getFacade()->hasPagePlaceholderMapping($formData[self::FIELD_FK_PAGE], $placeholder)) {
                    $context->addViolation('Placeholder has already mapped');
                }
            },
            'groups' => [self::GROUP_PLACEHOLDER_CHECK],
        ]);

        return $placeholderConstraints;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'cms_glossary';
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
