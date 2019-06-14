<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Plugin\FormExpander;

use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormFieldDependenciesPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Range;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\MerchantRelationshipSalesOrderThresholdGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig getConfig()
 */
class MerchantRelationshipSoftThresholdFixedFeeFormExpanderPlugin extends AbstractPlugin implements SalesOrderThresholdFormExpanderPluginInterface, SalesOrderThresholdFormFieldDependenciesPluginInterface
{
    protected const FIELD_SOFT_FIXED_FEE = 'fixedFee';

    /**
     * @api
     *
     * @return string
     */
    public function getThresholdName(): string
    {
        return 'Soft Threshold with fixed fee';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getThresholdKey(): string
    {
        return MerchantRelationshipSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_FIXED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getThresholdGroup(): string
    {
        return MerchantRelationshipSalesOrderThresholdGuiConfig::GROUP_SOFT;
    }

    /**
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this->addSoftFixedFeeField($builder, $options);

        return $builder;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     * @param array $data
     *
     * @return array
     */
    public function mapSalesOrderThresholdValueTransferToFormData(SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer, array $data): array
    {
        $data[static::FIELD_SOFT_FIXED_FEE] = $salesOrderThresholdValueTransfer->getFee();

        return $data;
    }

    /**
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer
     */
    public function mapFormDataToTransfer(array $data, SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer): SalesOrderThresholdValueTransfer
    {
        $salesOrderThresholdValueTransfer->setFee($data[static::FIELD_SOFT_FIXED_FEE])
            ->setSalesOrderThresholdType(
                (new SalesOrderThresholdTypeTransfer())
                    ->setKey($this->getThresholdKey())
                    ->setThresholdGroup($this->getThresholdGroup())
            );

        return $salesOrderThresholdValueTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSoftFixedFeeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SOFT_FIXED_FEE, MoneyType::class, [
            'label' => 'Enter fixed fee',
            'currency' => $options[MerchantRelationshipSalesOrderThresholdGuiConfig::OPTION_CURRENCY_CODE],
            'divisor' => 100,
            'constraints' => [
                new Range(['min' => 0]),
            ],
            'required' => false,
            'attr' => [
                'threshold_group' => $this->getThresholdGroup(),
                'threshold_key' => $this->getThresholdKey(),
            ],
        ]);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getThresholdFieldDependentFieldNames(): array
    {
        return [
            static::FIELD_SOFT_FIXED_FEE,
        ];
    }
}
