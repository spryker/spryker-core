<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form;

use DateTime;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ValidFromRangeConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ValidToRangeConstraint;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 */
class ProductConcreteBulkForm extends AbstractType
{
    protected const LABEL_IS_ACTIVE = 'Active';
    protected const LABEL_VALID_FROM = 'From';
    protected const LABEL_VALID_TO = 'To';

    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIsActiveField($builder)
            ->addValidFromField($builder)
            ->addValidToField($builder);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsActiveField(FormBuilderInterface $builder)
    {
        $builder
            ->add(ProductConcreteTransfer::IS_ACTIVE, CheckboxType::class, [
                'required' => false,
                'label' => static::LABEL_IS_ACTIVE,
            ]);

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidFromField(FormBuilderInterface $builder)
    {
        $builder->add(ProductConcreteTransfer::VALID_FROM, DateTimeType::class, [
            'required' => false,
            'label' => static::LABEL_VALID_FROM,
            'constraints' => [
                new ValidFromRangeConstraint(),
            ],
            'widget' => 'single_text',
        ]);

        $builder->get(ProductConcreteTransfer::VALID_FROM)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidToField(FormBuilderInterface $builder)
    {
        $builder->add(ProductConcreteTransfer::VALID_TO, DateTimeType::class, [
            'required' => false,
            'label' => static::LABEL_VALID_TO,
            'constraints' => [
                new ValidToRangeConstraint(),
            ],
            'widget' => 'single_text',
        ]);

        $builder->get(ProductConcreteTransfer::VALID_TO)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createDateTimeModelTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($value) {
                if ($value !== null) {
                    return new DateTime($value);
                }
            },
            function ($value) {
                if ($value instanceof DateTime) {
                    $value = $value->format(static::DATE_TIME_FORMAT);
                }

                return $value;
            }
        );
    }
}
