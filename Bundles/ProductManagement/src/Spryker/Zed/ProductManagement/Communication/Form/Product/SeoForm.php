<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Spryker\Zed\ProductManagement\Communication\Form\AbstractSubForm;
use Symfony\Component\Form\FormBuilderInterface;

class SeoForm extends AbstractSubForm
{
    const FIELD_META_TITLE = 'meta_title';
    const FIELD_META_KEYWORDS = 'meta_keywords';
    const FIELD_META_DESCRIPTION = 'meta_description';

    /**
     * @return string
     */
    public function getName()
    {
        return 'product_seo';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this
            ->addMetaTitleField($builder)
            ->addMetaKeywordField($builder)
            ->addMetaDescriptionField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMetaTitleField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_META_TITLE, 'text', [
                'required' => false,
                'label' => 'Title',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMetaKeywordField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_META_KEYWORDS, 'text', [
                'required' => false,
                'label' => 'Keywords',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMetaDescriptionField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_META_DESCRIPTION, 'text', [
                'required' => false,
                'label' => 'Description',
            ]);

        return $this;
    }
}
