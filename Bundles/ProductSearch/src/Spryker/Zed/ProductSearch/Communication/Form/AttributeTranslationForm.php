<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class AttributeTranslationForm extends AbstractType
{
    const FIELD_KEY_TRANSLATION = 'key_translation';

    /**
     * @return string The name of this type
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
        $this->addAttributeNameTranslationField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributeNameTranslationField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_KEY_TRANSLATION, 'text', [
            'label' => 'Filter name *',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
