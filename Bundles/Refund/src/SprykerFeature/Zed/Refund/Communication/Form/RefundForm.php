<?php

namespace SprykerFeature\Zed\Refund\Communication\Form;

use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Refund\Persistence\Propel\SpyRefund;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesExpense;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class RefundForm extends AbstractForm
{

    const FIELD_COMMENT = 'comment';
    const FIELD_AMOUNT = 'amount';
    const FIELD_ADJUSTMENT_FEE = 'adjustment_fee';

    const FIELD_ORDER_ITEMS = 'order_items';
    const FIELD_EXPENSES = 'expenses';

    /**
     * @var ObjectCollection
     */
    protected $orderItems;

    /**
     * @var ObjectCollection
     */
    protected $expenses;

    /**
     * @param ObjectCollection $orderItems
     * @param ObjectCollection $expenses
     */
    public function __construct(ObjectCollection $orderItems, ObjectCollection $expenses)
    {
        $this->orderItems = $orderItems;
        $this->expenses = $expenses;
    }

    /**
     * @return self
     */
    protected function buildFormFields()
    {
        $this->addCollection(self::FIELD_ORDER_ITEMS, $this->buildOrderItemsFieldConfiguration());
        $this->addCollection(self::FIELD_EXPENSES, $this->buildExpensesFieldConfiguration());

        $this
            ->addNumber(self::FIELD_ADJUSTMENT_FEE, [
                'label' => 'Adjustment Fee (in Cents)',
            ])
            ->addNumber(self::FIELD_AMOUNT, [
                'label' => 'Total Refund Amount (autocalculated / in Cents)',
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan(['value' => 0]),
                ],
                'attr' => ['readonly' => true],
            ])
            ->addTextarea(static::FIELD_COMMENT, [
                'label' => 'Comment',
                'attr' => [
                    'rows' => 7,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;

        return $this;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function buildOrderItemsFieldConfiguration()
    {
        $data = [];

        /** @var SpySalesOrderItem $orderItem */
        foreach ($this->orderItems as $orderItem) {
            if ($orderItem->getFkRefund() !== null) {
                continue;
            }

            $data[$orderItem->getIdSalesOrderItem()] = $orderItem->getQuantity();
        }

        return [
            'type' => 'number',
            'label' => false,
            'data' => $data,
            'constraints' => $this->getFieldDefaultConstraints(),
        ];
    }

    /**
     * @return array
     */
    protected function buildExpensesFieldConfiguration()
    {
        $data = [];

        /** @var SpySalesExpense $expense */
        foreach ($this->expenses as $expense) {
            if ($expense->getFkRefund() !== null) {
                continue;
            }

            $data[$expense->getIdSalesExpense()] = 1;
        }

        $attr = [];

        return [
            'type' => 'number',
            'label' => false,
            'data' => $data,
            'constraints' => $this->getFieldDefaultConstraints(),
            //'attr' => $attr
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
     * @return ObjectCollection|SpyRefund[]
     */
    protected function getRefunds($idOrder)
    {
        $this->refundQuery = new \SprykerFeature\Zed\Refund\Persistence\Propel\SpyRefundQuery();

        return $this->refundQuery
            ->filterByFkSalesOrder($idOrder)
            ->find()
        ;
    }

}
