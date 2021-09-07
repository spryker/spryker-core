<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryGui\CategoryGuiConfig getConfig()
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 */
class RootCategoryType extends CommonCategoryType
{
    /**
     * @var string
     */
    protected const OPTION_PROPERTY_PATH_CATEGORY_NODE_IS_ROOT = 'categoryNode.isRoot';

    /**
     * @var string
     */
    protected const FIELD_IS_ROOT = 'is_root';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->addIsRootField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsRootField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_ROOT, HiddenType::class, [
            'property_path' => static::OPTION_PROPERTY_PATH_CATEGORY_NODE_IS_ROOT,
        ]);

        return $this;
    }
}
