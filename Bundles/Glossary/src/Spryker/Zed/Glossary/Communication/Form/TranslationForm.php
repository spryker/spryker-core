<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Communication\Form;

use Spryker\Zed\Glossary\Business\GlossaryFacadeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TranslationForm extends AbstractType
{
    const FIELD_GLOSSARY_KEY = 'glossary_key';
    const FIELD_LOCALES = 'locales';

    const OPTION_LOCALES = 'locales';

    const GROUP_UNIQUE_GLOSSARY_KEY_CHECK = 'unique_glossary_key_check';

    const TYPE_DATA = 'data';

    /**
     * @var \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(GlossaryFacadeInterface $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'translation';
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
            ->addGlossaryKeyField($builder)
            ->addLocaleCollection($builder, $options[self::OPTION_LOCALES]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_GLOSSARY_KEY, 'text', [
            'label' => 'Name',
            'constraints' => $this->createGlossaryKeyConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $locales
     *
     * @return $this
     */
    protected function addLocaleCollection(FormBuilderInterface $builder, array $locales)
    {
        $builder->add(self::FIELD_LOCALES, 'collection', $this->buildLocaleFieldConfiguration(self::TYPE_DATA, $locales));

        return $this;
    }

    /**
     * @param string $dataTypeField
     * @param array $locales
     *
     * @return array
     */
    protected function buildLocaleFieldConfiguration($dataTypeField, array $locales)
    {
        $translationFields = array_fill_keys($locales, '');

        return [
            'type' => 'textarea',
            'label' => false,
            'required' => false,
            $dataTypeField => $translationFields,
            'constraints' => $this->getFieldDefaultConstraints(),
            'options' => [
                'attr' => [
                    'class' => 'html-editor',
                    'rows' => 10,
                ],
            ],
        ];
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
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(self::OPTION_LOCALES);

        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $defaultData = (array)$form->getConfig()->getData();
                $submittedData = $form->getData();

                if (array_key_exists(self::FIELD_GLOSSARY_KEY, $defaultData) === false ||
                    $defaultData[self::FIELD_GLOSSARY_KEY] !== $submittedData[self::FIELD_GLOSSARY_KEY]
                ) {
                    return [Constraint::DEFAULT_GROUP, self::GROUP_UNIQUE_GLOSSARY_KEY_CHECK];
                }

                return [Constraint::DEFAULT_GROUP];
            },
        ]);
    }

    /**
     * @deprecated Use `configureOptions()` instead.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function createGlossaryKeyConstraints()
    {
        $constraints = $this->getFieldDefaultConstraints();

        $constraints[] = new Callback([
            'methods' => [
                function ($glossaryKey, ExecutionContextInterface $contextInterface) {
                    if ($this->glossaryFacade->hasKey($glossaryKey)) {
                        $contextInterface->addViolation('Translation key already exists.');
                    }
                },
            ],
            'groups' => [self::GROUP_UNIQUE_GLOSSARY_KEY_CHECK],
        ]);

        return $constraints;
    }
}
