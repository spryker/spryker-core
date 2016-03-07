<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication\Form;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Spryker\Zed\Refund\Business\RefundFacade;
use Spryker\Zed\Refund\Dependency\Plugin\PaymentDataPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

class RefundForm extends AbstractForm
{

    const FIELD_COMMENT = 'comment';
    const FIELD_AMOUNT = 'amount';
    const FIELD_ADJUSTMENT_FEE = 'adjustment_fee';

    const FIELD_BIC = 'bic';
    const FIELD_IBAN = 'iban';

    const FIELD_ORDER_ITEMS = 'order_items';
    const FIELD_EXPENSES = 'expenses';

    /**
     * @var \Orm\Zed\Refund\Persistence\SpyRefundQuery
     */
    protected $refundQuery;

    /**
     * @var \Spryker\Zed\Refund\Business\RefundFacade
     */
    protected $refundFacade;

    /**
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $orderTransfer;

    /**
     * @var \Spryker\Zed\Refund\Dependency\Plugin\PaymentDataPluginInterface
     */
    protected $paymentDataPlugin;

    /**
     * @param \Spryker\Zed\Refund\Business\RefundFacade $refundFacade
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Spryker\Zed\Refund\Dependency\Plugin\PaymentDataPluginInterface $paymentDataPlugin
     */
    public function __construct(RefundFacade $refundFacade, OrderTransfer $orderTransfer, PaymentDataPluginInterface $paymentDataPlugin)
    {
        $this->refundFacade = $refundFacade;
        $this->orderTransfer = $orderTransfer;
        $this->paymentDataPlugin = $paymentDataPlugin;
    }

    /**
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'refund';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_ORDER_ITEMS, 'collection', $this->buildOrderItemsFieldConfiguration());
        $builder->add(self::FIELD_EXPENSES, 'collection', $this->buildExpensesFieldConfiguration());

        $maxAmount = $this->refundFacade->calculateRefundableAmount($this->orderTransfer);

        $builder
            ->add(self::FIELD_ADJUSTMENT_FEE, 'number', [
                'label' => 'Adjustment Fee (in Cents)',
            ])
            ->add(self::FIELD_AMOUNT, 'number', [
                'label' => 'Total Refund Amount (autocalculated / in Cents)',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                    $this->getConstraints()->createConstraintGreaterThan(['value' => 0]),
                    $this->getConstraints()->createConstraintLessThanOrEqual(['value' => $maxAmount]),
                ],
                'attr' => ['readonly' => true],
            ])
            ->add(static::FIELD_COMMENT, 'textarea', [
                'label' => 'Comment',
                'attr' => [
                    'rows' => 7,
                ],
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ]);

        if ($this->requiresPaymentData()) {
            $builder
                ->add(self::FIELD_IBAN, 'text', [
                    'constraints' => [
                        $this->getConstraints()->createConstraintNotBlank(),
                    ],
                ])
                ->add(self::FIELD_BIC, 'text', [
                    'constraints' => [
                        $this->getConstraints()->createConstraintNotBlank(),
                    ],
                ]);
        }
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        if (!$this->requiresPaymentData()) {
            return [];
        }

        $paymentData = $this->paymentDataPlugin->getPaymentData($this->orderTransfer->getIdSalesOrder());

        return [
            self::FIELD_IBAN => $paymentData->getIban(),
            self::FIELD_BIC => $paymentData->getBic(),
        ];
    }

    /**
     * @return array
     */
    protected function buildOrderItemsFieldConfiguration()
    {
        return [
            'type' => 'number',
            'label' => false,
            'data' => $this->getRefundableItems(),
            'constraints' => $this->getFieldDefaultConstraints(),
        ];
    }

    /**
     * @return array
     */
    protected function buildExpensesFieldConfiguration()
    {
        return [
            'type' => 'number',
            'label' => false,
            'data' => $this->getRefundableExpenses(),
            'constraints' => $this->getFieldDefaultConstraints(),
        ];
    }

    /**
     * @return array
     */
    protected function getFieldDefaultConstraints()
    {
        return [
        ];
    }

    /**
     * @param int $idOrder
     *
     * @return \Generated\Shared\Transfer\RefundTransfer[]
     */
    protected function getRefunds($idOrder)
    {
        return $this->refundFacade->getRefundsByIdSalesOrder($idOrder);
    }

    /**
     * @return array
     */
    protected function getRefundableItems()
    {
        $refundableItems = $this->refundFacade->getRefundableItems($this->orderTransfer->getIdSalesOrder());
        $data = [];

        foreach ($refundableItems as $item) {
            $data[$item->getIdSalesOrderItem()] = $item->getQuantity();
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function getRefundableExpenses()
    {
        $refundableExpenses = $this->refundFacade->getRefundableExpenses($this->orderTransfer->getIdSalesOrder());
        $data = [];

        foreach ($refundableExpenses as $expense) {
            $data[$expense->getIdSalesExpense()] = 1;
        }

        return $data;
    }

    /**
     * @return bool
     */
    protected function requiresPaymentData()
    {
        return $this->paymentDataPlugin->isPaymentDataRequired($this->orderTransfer);
    }

}
