<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete;

use Spryker\Zed\Gui\Communication\Form\Type\FormattedNumberType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementRepositoryInterface getRepository()
 */
class StockForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_HIDDEN_STOCK_PRODUCT_ID = 'id_stock_product';

    /**
     * @var string
     */
    public const FIELD_HIDDEN_FK_STOCK = 'fk_stock';

    /**
     * @var string
     */
    public const FIELD_TYPE = 'type';

    /**
     * @var string
     */
    public const FIELD_QUANTITY = 'quantity';

    /**
     * @var string
     */
    public const FIELD_IS_NEVER_OUT_OF_STOCK = 'is_never_out_of_stock';

    /**
     * @var string
     */
    protected const OPTION_LOCALE = 'locale';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addStockIdHiddenField($builder, $options)
            ->addProductStockIdHiddenField($builder, $options)
            ->addTypeField($builder, $options)
            ->addQuantityField($builder, $options)
            ->addIsNeverOutOfStockCheckbox($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            static::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addStockIdHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_HIDDEN_FK_STOCK, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addProductStockIdHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_HIDDEN_STOCK_PRODUCT_ID, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addTypeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_TYPE, TextType::class, [
            'label' => 'Type',
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
            'attr' => [
                'readonly' => 'readonly',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_QUANTITY, FormattedNumberType::class, [
            'label' => 'Quantity',
            'locale' => $options[static::OPTION_LOCALE],
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsNeverOutOfStockCheckbox(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_NEVER_OUT_OF_STOCK, CheckboxType::class, [
            'label' => 'Never out of stock',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $stockProduct = $form->getViewData();
        $stockType = $stockProduct[static::FIELD_TYPE];

        $mapping = $this->getFactory()->getStockFacade()->getWarehouseToStoreMapping();
        if (isset($mapping[$stockType])) {
            $view->vars['available_in_stores'] = $mapping[$stockType];
        }
    }
}
