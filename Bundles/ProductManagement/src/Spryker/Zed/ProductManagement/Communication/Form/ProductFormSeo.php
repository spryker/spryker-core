<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductFormSeo extends AbstractType
{

    const FIELD_META_TITLE = 'meta_title';
    const FIELD_META_KEYWORD = 'meta_keyword';
    const FIELD_META_DESCRIPTION = 'meta_description';

    const ATTRIBUTES = 'attributes';

    /**
     * @return string
     */
    public function getName()
    {
        return 'ProductFormSeo';
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
            ->add(self::FIELD_META_KEYWORD, 'text', [
                'required' => false,
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
            ]);

        return $this;
    }

}
