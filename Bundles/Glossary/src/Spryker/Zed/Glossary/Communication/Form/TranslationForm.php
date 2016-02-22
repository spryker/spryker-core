<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class TranslationForm extends AbstractType
{

    const FIELD_GLOSSARY_KEY = 'glossary_key';
    const FIELD_LOCALES = 'locales';

    const OPTION_LOCALES = 'locales';

    const TYPE_DATA = 'data';

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
        $builder->add(self::FIELD_GLOSSARY_KEY, new AutosuggestType(), [
            'label' => 'Name',
            'url' => '/glossary/key/suggest',
            'constraints' => $this->getFieldDefaultConstraints(),
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
            $dataTypeField => $translationFields,
            'constraints' => $this->getFieldDefaultConstraints(),
            'options' => [
                'attr' => [
                    'class' => 'html-editor',
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
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::OPTION_LOCALES);
    }

}
