<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Form;

use DateTime;
use Generated\Shared\Transfer\OrderTableCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\Sales\Communication\Form\DataProvider\TableFilterFormDataProvider;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 * @method \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 */
class TableFilterForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_STATUSES = 'statuses';

    /**
     * @var string
     */
    protected const FIELD_STORES = 'stores';

    /**
     * @var string
     */
    protected const FIELD_ORDER_DATE_FROM = 'order_date_from';

    /**
     * @var string
     */
    protected const FIELD_ORDER_DATE_TO = 'order_date_to';

    /**
     * @var string
     */
    protected const PLACEHOLDER_STATUSES = 'Select Statuses';

    /**
     * @var string
     */
    protected const PLACEHOLDER_STORES = 'Select Stores';

    /**
     * @var string
     */
    protected const LABEL_STORE = 'Store';

    /**
     * @var string
     */
    protected const LABEL_STATUS = 'Status';

    /**
     * @var string
     */
    protected const LABEL_ORDER_DATE_FROM = 'Order date from';

    /**
     * @var string
     */
    protected const LABEL_ORDER_DATE_TO = 'Order date to';

    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d\TH:i';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return '';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            TableFilterFormDataProvider::OPTION_STATUSES,
            TableFilterFormDataProvider::OPTION_STORES,
        ]);

        $resolver->setDefaults([
            'data_class' => OrderTableCriteriaTransfer::class,
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
            ->addStoreField($builder, $options)
            ->addOrderDateFromField($builder)
            ->addOrderDateToField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addStatusField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_STATUSES, ChoiceType::class, [
            'label' => static::LABEL_STATUS,
            'placeholder' => static::PLACEHOLDER_STATUSES,
            'required' => false,
            'multiple' => true,
            'expanded' => false,
            'choices' => $options[TableFilterFormDataProvider::OPTION_STATUSES] ?? [],
            'attr' => [
                'class' => 'spryker-form-select2combobox',
                'data-placeholder' => $this->getFactory()->getTranslatorFacade()->trans(static::PLACEHOLDER_STATUSES),
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
            'choices' => $options[TableFilterFormDataProvider::OPTION_STORES] ?? [],
            'attr' => [
                'class' => 'spryker-form-select2combobox',
                'data-placeholder' => $this->getFactory()->getTranslatorFacade()->trans(static::PLACEHOLDER_STORES),
                'data-clearable' => true,
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOrderDateFromField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ORDER_DATE_FROM, DateTimeType::class, [
            'label' => static::LABEL_ORDER_DATE_FROM,
            'widget' => 'single_text',
            'required' => false,
            'html5' => true,
        ]);

        $builder->get(static::FIELD_ORDER_DATE_FROM)
            ->addModelTransformer($this->createDateTimeTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOrderDateToField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ORDER_DATE_TO, DateTimeType::class, [
            'label' => static::LABEL_ORDER_DATE_TO,
            'widget' => 'single_text',
            'required' => false,
            'html5' => true,
        ]);

        $builder->get(static::FIELD_ORDER_DATE_TO)
            ->addModelTransformer($this->createDateTimeTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    protected function createDateTimeTransformer(): DataTransformerInterface
    {
        return new CallbackTransformer(
            function ($dateAsString) {
                if (!$dateAsString) {
                    return null;
                }

                if ($dateAsString instanceof DateTime) {
                    return $dateAsString;
                }

                return new DateTime($dateAsString);
            },
            function ($dateAsObject) {
                if (!$dateAsObject) {
                    return null;
                }

                if ($dateAsObject instanceof DateTime) {
                    return $dateAsObject->format(static::DATE_TIME_FORMAT);
                }

                return $dateAsObject;
            },
        );
    }
}
