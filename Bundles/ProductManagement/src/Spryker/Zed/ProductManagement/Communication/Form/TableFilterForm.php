<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\TableFilterFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 */
class TableFilterForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_STATUS = 'status';

    /**
     * @var string
     */
    protected const FIELD_STORES = 'stores';

    /**
     * @var string
     */
    protected const LABEL_STATUS = 'Variant Status';

    /**
     * @var string
     */
    protected const LABEL_STORE = 'Store';

    /**
     * @var string
     */
    protected const PLACEHOLDER_STATUS = 'Select Status';

    /**
     * @var string
     */
    protected const PLACEHOLDER_STORES = 'Select Stores';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductTableCriteriaTransfer::class,
            'csrf_protection' => false,
        ]);

        $resolver->setRequired([
            TableFilterFormDataProvider::OPTION_STATUS_CHOICES,
            TableFilterFormDataProvider::OPTION_STORE_CHOICES,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod(Request::METHOD_GET);

        $this
            ->addStatusField($builder, $options)
            ->addStoresField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addStatusField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_STATUS, ChoiceType::class, [
            'label' => static::LABEL_STATUS,
            'placeholder' => static::PLACEHOLDER_STATUS,
            'required' => false,
            'choices' => $options[TableFilterFormDataProvider::OPTION_STATUS_CHOICES],
            'multiple' => false,
            'attr' => [
                'class' => 'spryker-form-select2combobox spryker-form-select2combobox--table-filter-form',
                'data-placeholder' => $this->getFactory()->getTranslatorFacade()->trans(static::PLACEHOLDER_STATUS),
                'data-disable-search' => 'true',
                'data-clearable' => true,
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
    protected function addStoresField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_STORES, ChoiceType::class, [
            'label' => static::LABEL_STORE,
            'placeholder' => static::PLACEHOLDER_STORES,
            'required' => false,
            'choices' => $options[TableFilterFormDataProvider::OPTION_STORE_CHOICES],
            'multiple' => true,
            'attr' => [
                'class' => 'spryker-form-select2combobox spryker-form-select2combobox--table-filter-form',
                'data-placeholder' => $this->getFactory()->getTranslatorFacade()->trans(static::PLACEHOLDER_STORES),
                'data-clearable' => true,
            ],
        ]);

        return $this;
    }
}
