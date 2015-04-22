<?php
namespace SprykerFeature\Zed\Salesrule\Business\Calculator;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Zed\Salesrule\Business\Model\Action\AbstractAction;
use SprykerFeature\Zed\Salesrule\Business\Model\Condition\VoucherCodeInPool;
use SprykerFeature\Zed\Salesrule\Business\Model\Logger;
use SprykerFeature\Zed\Salesrule\Business\SalesruleFacade;

class SalesruleCalculator
{
    /**
     * @var SalesruleFacade
     */
    protected $facadeSalesrule;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @param FactoryInterface $factory
     * @param SalesruleFacade $facadeSalesrule
     */
    public function __construct(FactoryInterface $factory, SalesruleFacade $facadeSalesrule)
    {
        $this->factory = $factory;
        $this->facadeSalesrule = $facadeSalesrule;
    }


    /**
     * @param Order $order
     */
    public function recalculate(Order $order)
    {
        $this->validateCodes($order);

        $localSalesrules = $this->facadeSalesrule->getActiveSalesrules(\SprykerFeature\Zed\Salesrule\Persistence\Propel\Map\SpySalesruleTableMap::COL_SCOPE_LOCAL);
        $localUsedCodes = $this->calculateSalesrules($order, $localSalesrules);

        $globalSalesrules = $this->facadeSalesrule->getActiveSalesrules(\SprykerFeature\Zed\Salesrule\Persistence\Propel\Map\SpySalesruleTableMap::COL_SCOPE_GLOBAL);
        $globalUsedCodes = $this->calculateSalesrules($order, $globalSalesrules);

        $allUsedCodes = array_unique(array_merge($localUsedCodes, $globalUsedCodes));
        $order->setCouponCodes($allUsedCodes);
    }

    /**
     * @param Order $order
     * @param $salesrules
     * @return array
     */
    protected function calculateSalesrules(Order $order, $salesrules)
    {
        $usedCodes = [];
        foreach ($salesrules as $salesrule) {
            $codes = $this->findUsedCodes($order, $salesrule);
            $amount = $this->calculateSalesrule($order, $salesrule, $codes);
            if (!empty($codes) && $amount > 0) {
                $usedCodes = array_merge($usedCodes, $codes);
            }
        }
        return $usedCodes;
    }

    /**
     * @param Order $order
     * @param \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule
     * @param array $codes
     * @return int
     */
    protected function calculateSalesrule(Order $order, \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule, array $codes)
    {
        $stack = $this->facadeSalesrule->getConditionStack($order, $salesrule);
        $discount = 0;
        if ($stack->canExecuteAction()) {
            $methodName = 'createModel' . $salesrule->getAction();
            /* @var AbstractAction $action */
            $action = $this->factory->$methodName($order, $salesrule, $codes);
            $discount = $action->execute();
            Logger::getInstance()->log($salesrule->getName() . ': ' . $salesrule->getAction() . ' calculated a discount of ' . $discount . PHP_EOL);
        }
        return $discount;
    }

    /**
     * @param Order $order
     */
    protected function validateCodes(Order $order)
    {
        $codes = $order->getCouponCodes();
        foreach ($codes as $key => $code) {
            if (!$this->facadeSalesrule->canUseCouponCode($code)) {
                unset($codes[$key]);
            }
        }
        $order->setCouponCodes($codes);
    }

    /**
     * @param Order $order
     * @param \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule
     * @return array
     */
    protected function findUsedCodes(Order $order, \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule)
    {
        $codes = [];
        $stack = $this->facadeSalesrule->getConditionStack($order, $salesrule);
        foreach ($stack as $condition) {
            if ($condition instanceof VoucherCodeInPool) {
                $condition->match($order);
                if (($code = $condition->getLastMatchedCode())) {
                    $codes[] = $code;
                }
            }
        }
        return $codes;
    }
}
