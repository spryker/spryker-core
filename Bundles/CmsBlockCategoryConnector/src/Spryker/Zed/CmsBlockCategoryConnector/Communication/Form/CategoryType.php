<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{

    const FIELD_CMS_BLOCKS = 'id_cms_blocks';

    const OPTION_CMS_BLOCK_LIST = 'option-cms-block-list';
    const OPTION_CMS_BLOCK_POSITION_LIST = 'option-cms-block-position-list';

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms-blocks';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCmsBlockFields($builder, $options[static::OPTION_CMS_BLOCK_POSITION_LIST], $options[static::OPTION_CMS_BLOCK_LIST]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(static::OPTION_CMS_BLOCK_LIST)
            ->setRequired(static::OPTION_CMS_BLOCK_POSITION_LIST);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $positions
     * @param array $choices
     *
     * @return $this
     */
    protected function addCmsBlockFields(FormBuilderInterface $builder, array $positions, array $choices)
    {
        foreach ($positions as $positionKey => $positionName) {
            $builder->add(static::FIELD_CMS_BLOCKS . '_' . $positionKey, new Select2ComboBoxType(), [
                'property_path' => static::FIELD_CMS_BLOCKS . '[' . $positionKey. ']',
                'label' => 'CMS Blocks: ' . $positionName,
                'choices' => $choices,
                'multiple' => true,
                'required' => false,
            ]);
        }

        return $this;
    }

}
