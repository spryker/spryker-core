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

class CmsBlockType extends AbstractType
{

    const FIELD_CATEGORIES = 'id_categories';

    const OPTION_CATEGORY_ARRAY = 'option-category-array';
    const OPTION_CMS_BLOCK_POSITION_LIST = 'option-cms-block-position-list';

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
        $this->addCategoryFields(
            $builder,
            $options[static::OPTION_CMS_BLOCK_POSITION_LIST],
            $options[static::OPTION_CATEGORY_ARRAY]
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(static::OPTION_CATEGORY_ARRAY)
            ->setRequired(static::OPTION_CMS_BLOCK_POSITION_LIST);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $positions
     * @param array $choices
     *
     * @return $this
     */
    protected function addCategoryFields(FormBuilderInterface $builder, array $positions, array $choices)
    {
        foreach ($positions as $positionKey => $positionName) {
            $builder->add(static::FIELD_CATEGORIES . '_' . $positionKey, new Select2ComboBoxType(), [
                'property_path' => static::FIELD_CATEGORIES . '[' . $positionKey . ']',
                'label' => 'Categories: ' . $positionName,
                'choices' => $choices,
                'multiple' => true,
                'required' => false,
            ]);
        }

        return $this;
    }

}
