<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Plugin\FormExpander;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface;
use Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Range;

/**
 * @method \Spryker\Zed\SalesOrderThresholdGui\Communication\SalesOrderThresholdGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig getConfig()
 */
class GlobalSoftThresholdFlexibleFeeFormExpanderPlugin extends AbstractPlugin implements SalesOrderThresholdFormExpanderPluginInterface
{
    protected const FIELD_SOFT_FLEXIBLE_FEE = 'flexibleFee';

    protected const TEMPLATE_PATH = '@SalesOrderThresholdGui/ThresholdStrategy/flexible-fee.twig';

    /**
     * @api
     *
     * @return string
     */
    public function getThresholdName(): string
    {
        return 'Soft Threshold with flexible fee';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getThresholdKey(): string
    {
        return SalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getThresholdGroup(): string
    {
        return SalesOrderThresholdGuiConfig::GROUP_SOFT;
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
        $this->addSoftFlexibleFeeField($builder, $options);

        return $builder;
    }

    /**
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return array
     */
    public function getData(array $data, SalesOrderThresholdTransfer $salesOrderThresholdTransfer): array
    {
        $data[static::FIELD_SOFT_FLEXIBLE_FEE] = $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getFee();

        return $data;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function mapData(SalesOrderThresholdTransfer $salesOrderThresholdTransfer, array $data): SalesOrderThresholdTransfer
    {
        $salesOrderThresholdValueTransfer = $salesOrderThresholdTransfer->getSalesOrderThresholdValue();
        $salesOrderThresholdValueTransfer->setFee($data[static::FIELD_SOFT_FLEXIBLE_FEE])
        ->setSalesOrderThresholdType(
            (new SalesOrderThresholdTypeTransfer())
            ->setKey($this->getThresholdKey())
            ->setThresholdGroup($this->getThresholdGroup())
        );

        $salesOrderThresholdTransfer->setSalesOrderThresholdValue($salesOrderThresholdValueTransfer);

        return $salesOrderThresholdTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSoftFlexibleFeeField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_SOFT_FLEXIBLE_FEE, PercentType::class, [
            'label' => 'Enter flexible fee',
            'type' => 'integer',
            'required' => false,
            'attr' => [
                'threshold_group' => $this->getThresholdGroup(),
                'threshold_key' => $this->getThresholdKey(),
                'template_path' => $this->getTemplatePath(),
            ],
            'constraints' => [
                $this->createRangeConstraint($options, 0, 100),
            ],
        ]);

        return $this;
    }

    /**
     * @param array $options
     * @param int $min
     * @param int $max
     *
     * @return \Symfony\Component\Validator\Constraints\Range
     */
    protected function createRangeConstraint(array $options, int $min, int $max): Range
    {
        return new Range([
            'min' => $min,
            'max' => $max,
        ]);
    }

    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return static::TEMPLATE_PATH;
    }
}
