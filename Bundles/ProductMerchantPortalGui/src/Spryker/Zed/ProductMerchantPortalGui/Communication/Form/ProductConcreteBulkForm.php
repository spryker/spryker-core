<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ValidFromRangeConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ValidToRangeConstraint;
use Spryker\Zed\ZedUi\Communication\Form\Type\DateTimeIso8601Type;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 */
class ProductConcreteBulkForm extends AbstractType
{
    /**
     * @var string
     */
    protected const LABEL_IS_ACTIVE = 'Active';

    /**
     * @var string
     */
    protected const LABEL_VALID_FROM = 'From';

    /**
     * @var string
     */
    protected const LABEL_VALID_TO = 'To';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
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
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
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
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addValidFromField(FormBuilderInterface $builder)
    {
        $builder->add(ProductConcreteTransfer::VALID_FROM, DateTimeIso8601Type::class, [
            'required' => false,
            'label' => static::LABEL_VALID_FROM,
            'constraints' => [
                new ValidFromRangeConstraint(),
            ],
            'widget' => 'single_text',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addValidToField(FormBuilderInterface $builder)
    {
        $builder->add(ProductConcreteTransfer::VALID_TO, DateTimeIso8601Type::class, [
            'required' => false,
            'label' => static::LABEL_VALID_TO,
            'constraints' => [
                new ValidToRangeConstraint(),
            ],
            'widget' => 'single_text',
        ]);

        return $this;
    }
}
