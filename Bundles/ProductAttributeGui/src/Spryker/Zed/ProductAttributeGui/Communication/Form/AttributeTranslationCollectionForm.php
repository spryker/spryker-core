<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Attribute;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AttributeTranslationCollectionForm extends AbstractType
{

    const FIELD_TRANSLATIONS = 'translations';

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
        $this
            ->addTranslationsFields($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTranslationsFields(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_TRANSLATIONS, 'collection', [
            'type' => new AttributeTranslationForm(),
            'options' => [],
        ]);

        return $this;
    }

}
