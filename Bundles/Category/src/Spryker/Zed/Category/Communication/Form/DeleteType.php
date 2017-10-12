<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DeleteType extends AbstractType
{
    const FIELD_PK_CATEGORY_NODE = 'id_category_node';
    const FIELD_FK_NODE_CATEGORY = 'fk_category';

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
        $builder->add(self::FIELD_PK_CATEGORY_NODE, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkNodeCategoryField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_FK_NODE_CATEGORY, 'hidden');

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'categoryDelete';
    }
}
