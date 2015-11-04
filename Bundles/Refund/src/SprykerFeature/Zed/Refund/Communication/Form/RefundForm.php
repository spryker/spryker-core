<?php

namespace SprykerFeature\Zed\Refund\Communication\Form;

use Generated\Shared\Refund\RefundInterface;
use Generated\Shared\Refund\OrderInterface;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Refund\Business\RefundFacade;
use SprykerFeature\Zed\Refund\Dependency\Plugin\PaymentDataPluginInterface;
use Orm\Zed\Refund\Persistence\SpyRefundQuery;

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
     * @var  SpyRefundQuery
     */
    protected $refundQuery;

    /**
     * @var RefundFacade
     */
    protected $refundFacade;

    /**
     * @var OrderInterface
     */
    protected $orderTransfer;

    /**
     * @var PaymentDataPluginInterface
     */
    protected $paymentDataPlugin;

    /**
     * @param RefundFacade $refundFacade
     * @param OrderInterface $orderTransfer
     * @param PaymentDataPluginInterface $paymentDataPlugin
     */
    public function __construct(RefundFacade $refundFacade, OrderInterface $orderTransfer, PaymentDataPluginInterface $paymentDataPlugin)
    {
        $this->refundFacade = $refundFacade;
        $this->orderTransfer = $orderTransfer;
        $this->paymentDataPlugin = $paymentDataPlugin;
    }

    /**
     * @return self
     */
    protected function buildFormFields()
    {
        $this->addCollection(self::FIELD_ORDER_ITEMS, $this->buildOrderItemsFieldConfiguration());
        $this->addCollection(self::FIELD_EXPENSES, $this->buildExpensesFieldConfiguration());

        $maxAmount = $this->refundFacade->calculateRefundableAmount($this->orderTransfer);

        $this
            ->addNumber(self::FIELD_ADJUSTMENT_FEE, [
                'label' => 'Adjustment Fee (in Cents)',
            ])
            ->addNumber(self::FIELD_AMOUNT, [
                'label' => 'Total Refund Amount (autocalculated / in Cents)',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                    $this->getConstraints()->createConstraintGreaterThan(['value' => 0]),
                    $this->getConstraints()->createConstraintLessThanOrEqual(['value' => $maxAmount]),
                ],
                'attr' => ['readonly' => true],
            ])
            ->addTextarea(static::FIELD_COMMENT, [
                'label' => 'Comment',
                'attr' => [
                    'rows' => 7,
                ],
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
        ;

        if ($this->requiresPaymentData()) {
            $this
                ->addText(self::FIELD_IBAN, [
                    'constraints' => [
                        $this->getConstraints()->createConstraintNotBlank(),
                    ],
                ])
                ->addText(self::FIELD_BIC, [
                    'constraints' => [
                        $this->getConstraints()->createConstraintNotBlank(),
                    ],
                ])
            ;
        }

        return $this;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
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
     * @param $idOrder
     *
     * @return RefundInterface[]
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
