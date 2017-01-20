<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form;

use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CmsGlossaryFormType extends AbstractType
{

    const FIELD_ID_CMS_PAGE = 'idCmsPage';
    const FIELD_PLACEHOLDER = 'placeholder';
    const FIELD_GLOSSARY_KEY = 'glossary_key';
    const FIELD_ID_KEY_MAPPING = 'idCmsGlossaryKeyMapping';
    const FIELD_TEMPLATE_NAME = 'templateName';
    const FIELD_SEARCH_OPTION = 'search_option';
    const FIELD_TRANSLATION = 'translation';

    const TYPE_GLOSSARY_NEW = 'New glossary';
    const TYPE_GLOSSARY_FIND = 'Find glossary';
    const TYPE_AUTO_GLOSSARY = 'Auto';
    const TYPE_FULLTEXT_SEARCH = 'Full text';

    const GROUP_PLACEHOLDER_CHECK = 'placeholder_check';
    const FIELD_FK_LOCALE = 'fk_locale';

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @param \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface $cmsFacade
     */
    public function __construct(CmsGuiToCmsInterface $cmsFacade)
    {
        $this->cmsFacade = $cmsFacade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_glossary';
    }

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

                if (!isset($defaultData[static::FIELD_ID_KEY_MAPPING])) {
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
        $builder->add(static::FIELD_ID_CMS_PAGE, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPlaceholderField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PLACEHOLDER, 'text', [
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
        $builder->add(static::FIELD_GLOSSARY_KEY, 'text');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCmsGlossaryKeyMappingField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_KEY_MAPPING, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTemplateNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TEMPLATE_NAME, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSearchOptionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SEARCH_OPTION, 'choice', [
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
        $builder->add(static::FIELD_FK_LOCALE, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTranslationField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TRANSLATION, 'textarea', [
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
            'methods' => [
                function ($placeholder, ExecutionContextInterface $context) {
                    $formData = $context->getRoot()->getViewData();
                    if ($this->cmsFacade->hasPagePlaceholderMapping($formData[static::FIELD_ID_CMS_PAGE], $placeholder)) {
                        $context->addViolation('Placeholder has already mapped');
                    }
                },
            ],
            'groups' => [static::GROUP_PLACEHOLDER_CHECK],
        ]);

        return $placeholderConstraints;
    }

}
