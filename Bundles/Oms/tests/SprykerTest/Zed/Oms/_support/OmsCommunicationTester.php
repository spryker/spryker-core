<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms;

use Codeception\Actor;
use Generated\Shared\Transfer\OrderTransfer;
use Silex\Application;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\Oms\Communication\Controller\TriggerController;
use Spryker\Zed\Oms\Communication\OmsCommunicationFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class OmsCommunicationTester extends Actor
{
    use _generated\OmsCommunicationTesterActions;

    /**
     * @return \Spryker\Zed\Oms\Communication\Controller\TriggerController
     */
    public function createTriggerController(): TriggerController
    {
        $triggerController = new TriggerController();
        $triggerController->setApplication(new Application());

        return $triggerController;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    public function getOmsFacade(): OmsFacadeInterface
    {
        return $this->getLocator()->oms()->facade();
    }

    /**
     * @param string[] $processes
     *
     * @return void
     */
    public function haveTestStatemachine(array $processes): void
    {
        $this->configureTestStateMachine($processes);
    }

    /**
     * @param array $orderData
     * @param string $omsProcessName
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function haveOrderTransfer(array $orderData, string $omsProcessName): OrderTransfer
    {
        $saveOrderTransfer = $this->haveOrder($orderData, $omsProcessName);

        return $this->getSalesFacade()->findOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getLocator()->sales()->facade();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createOmsTriggerForm(): FormInterface
    {
        return $this->createOmsCommunicationFactory()->createOmsTriggerForm();
    }

    /**
     * @return \Spryker\Zed\Oms\Communication\OmsCommunicationFactory
     */
    protected function createOmsCommunicationFactory(): OmsCommunicationFactory
    {
        return new OmsCommunicationFactory();
    }
}
