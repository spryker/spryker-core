<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Definition;

use Generated\Zed\Ide\FactoryAutoCompletion\CheckoutBusiness;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Library\Workflow\TaskInterface;

class SaveOrder extends AbstractDefinition
{
    /**
     * @var CheckoutBusiness
     */
    protected $factory;

    /**
     * @return TaskInterface[]
     */
    protected function getTasks()
    {

        $locator = Locator::getInstance();
//        $itemGrouper = $this->factory->createModelWorkflowTaskHelperItemGrouper(new Locator());
        return [
//            $this->factory->createModelWorkflowTaskValidateOrderIsNew(),
//            $this->factory->createModelWorkflowTaskValidateStock($itemGrouper),
//            $this->factory->createModelWorkflowTaskValidateOrderIsCalculated($locator),
//            $this->factory->createModelWorkflowTaskPropelBeginTransaction(),
//            $this->factory->createModelWorkflowTaskSaveCustomerIfNew(),
//            $this->factory->createModelWorkflowTaskPrepareBillingAddress($locator),
//            $this->factory->createModelWorkflowTaskPrepareShippingAddress($locator),
//            $this->factory->createModelWorkflowTaskEnsureNewSalesOrderAddresses(),
//            $this->factory->createModelWorkflowTaskAssignCountryToAddress($locator),
//            $this->factory->createModelWorkflowTaskSaveOrder($locator),
//            $this->factory->createModelWorkflowTaskPropelCommitTransaction(),
//            $this->factory->createModelWorkflowTaskStateMachineStartStateMachine(),
        ];
    }

}
