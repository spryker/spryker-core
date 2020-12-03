<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryGui\CategoryGuiConfig getConfig()
 */
class DeleteType extends AbstractType
{
    public const FIELD_PK_CATEGORY_NODE = 'id_category_node';
    public const FIELD_FK_NODE_CATEGORY = 'fk_category';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addPkCategoryNodeField($builder)
            ->addFkNodeCategoryField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPkCategoryNodeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PK_CATEGORY_NODE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkNodeCategoryField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_NODE_CATEGORY, HiddenType::class);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'categoryDelete';
    }
}
