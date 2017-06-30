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

class CategoryType extends AbstractType
{

    const FIELD_CMS_BLOCKS = 'id_cms_blocks';

    const OPTION_CMS_BLOCK_LIST = 'option-cms-block-list';

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
        $this->addCmsBlocksField($builder, $options[static::OPTION_CMS_BLOCK_LIST]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_CMS_BLOCK_LIST);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addCmsBlocksField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_CMS_BLOCKS, new Select2ComboBoxType(), [
            'label' => 'CMS Blocks',
            'choices' => $choices,
            'multiple' => true,
            'required' => false,
        ]);

        return $this;
    }

}
