<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Glossary\Communication\Form;

use Symfony\Component\Form\FormBuilderInterface;

class UpdateTranslationForm extends TranslationForm
{
    const TYPE_DATA_EMPTY = 'empty_data';

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_GLOSSARY_KEY, 'text', [
            'label' => 'Name',
            'attr' => [
                'readonly' => 'readonly',
            ],
        ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $locales
     *
     * @return UpdateTranslationForm
     */
    protected function addLocaleCollection(FormBuilderInterface $builder, array $locales)
    {
        $builder->add(self::FIELD_LOCALES, 'collection', $this->buildLocaleFieldConfiguration(self::TYPE_DATA_EMPTY, $locales));

        return $this;
    }

}
