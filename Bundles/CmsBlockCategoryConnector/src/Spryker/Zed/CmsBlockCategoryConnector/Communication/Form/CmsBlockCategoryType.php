<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsBlockCategoryType extends AbstractType
{

    const FIELD_ID_CMS_BLOCK = 'id_cms_block';
    const FIELD_CATEGORIES = 'id_categories';

    const OPTION_CATEGORY_ARRAY = 'option-category-array';

    /**
     * @return string
     */
    public function getName()
    {
        return 'categories';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCategoriesField($builder, $options[static::OPTION_CATEGORY_ARRAY]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_CATEGORY_ARRAY);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addCategoriesField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_CATEGORIES, new Select2ComboBoxType(), [
            'label' => 'Categories',
            'choices' => $choices,
            'multiple' => true,
            'required' => false,
        ]);

        return $this;
    }

}
