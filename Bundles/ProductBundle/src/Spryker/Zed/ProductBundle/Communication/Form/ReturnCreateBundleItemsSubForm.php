<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Form;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductBundle\Communication\Form\DataProvider\ProductBundleReturnCreateFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SalesReturnGui\Communication\SalesReturnGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesReturnGui\SalesReturnGuiConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface getQueryContainer()
 */
class ReturnCreateBundleItemsSubForm extends AbstractType
{
    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Form\ReturnCreateItemsSubForm::FIELD_CUSTOM_REASON
     */
    public const FIELD_CUSTOM_REASON = 'customReason';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            ProductBundleReturnCreateFormDataProvider::OPTION_RETURN_REASONS,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIsReturnableField($builder)
            ->addReasonField($builder, $options)
            ->addCustomReasonField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsReturnableField(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $event->getForm()->add(ItemTransfer::IS_RETURNABLE, CheckboxType::class, [
                'label' => false,
                'required' => false,
                'disabled' => !$this->isBundleReturnable($event->getData()[ProductBundleReturnCreateFormDataProvider::BUNDLE_ITEMS]),
            ]);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addReasonField(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $event->getForm()->add(ReturnItemTransfer::REASON, ChoiceType::class, [
                'label' => false,
                'placeholder' => 'Select reason',
                'choices' => $options[ProductBundleReturnCreateFormDataProvider::OPTION_RETURN_REASONS],
                'required' => false,
                'disabled' => !$this->isBundleReturnable($event->getData()[ProductBundleReturnCreateFormDataProvider::BUNDLE_ITEMS]),
            ]);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCustomReasonField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CUSTOM_REASON, TextareaType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return bool
     */
    protected function isBundleReturnable(array $itemTransfers): bool
    {
        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getIsReturnable()) {
                return false;
            }
        }

        return true;
    }
}
