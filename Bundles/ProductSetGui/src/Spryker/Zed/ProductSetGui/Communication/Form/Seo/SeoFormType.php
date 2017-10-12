<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\Seo;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class SeoFormType extends AbstractType
{
    const FIELD_LOCALIZED_SEO_FORM_COLLECTION = 'localized_seo_form_collection';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addLocalizedSeoFormCollection($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocalizedSeoFormCollection(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_LOCALIZED_SEO_FORM_COLLECTION, CollectionType::class, [
            'type' => LocalizedSeoFormType::class,
        ]);

        return $this;
    }
}
