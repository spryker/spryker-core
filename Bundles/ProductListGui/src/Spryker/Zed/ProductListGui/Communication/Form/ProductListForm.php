<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form;

use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class ProductListForm extends AbstractType
{
    /** @see SpyProductListTableMap::COL_ID_PRODUCT_LIST */
    public const FIELD_ID = 'id_product_list';
    /** @see SpyProductListTableMap::COL_TITLE */
    public const FIELD_NAME = 'title';
    /** @see SpyProductListTableMap::COL_TYPE */
    public const FIELD_TYPE = 'type';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'productListForm';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addIdField($builder)
            ->addNameField($builder)
            ->addTypeFiled($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ID, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'Name',
            'constraints' => [
                new Required(),
                new NotBlank(),
                new Length(['max' => 100]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTypeFiled(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_TYPE, ChoiceType::class, [
            'label' => 'Type',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Whitelist' => SpyProductListTableMap::COL_TYPE_WHITELIST,
                'Blacklist' => SpyProductListTableMap::COL_TYPE_BLACKLIST,
            ],
        ]);

        return $this;
    }
}
