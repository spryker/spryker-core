<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form;

use Generated\Shared\Transfer\MerchantTableCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\MerchantGui\Communication\Form\DataProvider\MerchantFilterFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantGui\MerchantGuiConfig getConfig()
 */
class MerchantTableFilterForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_STATUS = 'status';

    /**
     * @var string
     */
    protected const FIELD_APPROVAL_STATUSES = 'approval_statuses';

    /**
     * @var string
     */
    protected const FIELD_STORES = 'stores';

    /**
     * @var string
     */
    protected const PLACEHOLDER_STATUS = 'Select Status';

    /**
     * @var string
     */
    protected const PLACEHOLDER_APPROVAL_STATUSES = 'Select Approval Statuses';

    /**
     * @var string
     */
    protected const PLACEHOLDER_STORES = 'Select Stores';

    /**
     * @var string
     */
    protected const LABEL_STORE = 'Store Filter';

    /**
     * @var string
     */
    protected const LABEL_STATUS = 'Status';

    /**
     * @var string
     */
    protected const LABEL_APPROVAL_STATUS = 'Approval Status';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            MerchantFilterFormDataProvider::OPTION_STATUSES,
            MerchantFilterFormDataProvider::OPTION_APPROVAL_STATUSES,
            MerchantFilterFormDataProvider::OPTION_STORES,
        ]);

        $resolver->setDefaults([
            'data_class' => MerchantTableCriteriaTransfer::class,
            'csrf_protection' => false,
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
            ->addApprovalStatusField($builder, $options)
            ->addStoreField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addStatusField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_STATUS, ChoiceType::class, [
            'label' => static::LABEL_STATUS,
            'placeholder' => $this->getFactory()->getTranslatorFacade()->trans(static::PLACEHOLDER_STATUS),
            'required' => false,
            'expanded' => false,
            'choices' => $options[MerchantFilterFormDataProvider::OPTION_STATUSES] ?? [],
            'attr' => [
                'class' => 'spryker-form-select2combobox',
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
    protected function addApprovalStatusField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_APPROVAL_STATUSES, ChoiceType::class, [
            'label' => static::LABEL_APPROVAL_STATUS,
            'placeholder' => static::PLACEHOLDER_APPROVAL_STATUSES,
            'required' => false,
            'multiple' => true,
            'expanded' => false,
            'choices' => $options[MerchantFilterFormDataProvider::OPTION_APPROVAL_STATUSES] ?? [],
            'attr' => [
                'class' => 'spryker-form-select2combobox',
                'data-placeholder' => $this->getFactory()->getTranslatorFacade()->trans(static::PLACEHOLDER_APPROVAL_STATUSES),
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
    protected function addStoreField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_STORES, ChoiceType::class, [
            'label' => static::LABEL_STORE,
            'placeholder' => static::PLACEHOLDER_STORES,
            'required' => false,
            'multiple' => true,
            'expanded' => false,
            'choices' => $options[MerchantFilterFormDataProvider::OPTION_STORES] ?? [],
            'attr' => [
                'class' => 'spryker-form-select2combobox',
                'data-placeholder' => $this->getFactory()->getTranslatorFacade()->trans(static::PLACEHOLDER_STORES),
                'data-form-autosubmit' => true,
                'data-clearable' => true,
            ],
        ]);

        return $this;
    }
}
