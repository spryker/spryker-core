<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Communication\Controller;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Communication
 * @group Controller
 * @group TriggerControllerTest
 * Add your own group annotations below this line
 */
class TriggerControllerTest extends Unit
{
    protected const OMS_ACTIVE_PROCESS = 'Test01';

    protected const QUERY_PARAM_ID_SALES_ORDER = 'id-sales-order';
    protected const QUERY_PARAM_ID_SALES_ORDER_ITEM = 'id-sales-order-item';
    protected const QUERY_PARAM_EVENT = 'event';
    protected const QUERY_PARAM_REDIRECT = 'redirect';
    protected const QUERY_PARAM_TOKEN = '_token';

    protected const VALUE_QUERY_PARAM_REDIRECT = '/bundle/controller/action';

    /**
     * @var \SprykerTest\Zed\Oms\OmsCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Oms\Communication\Controller\TriggerController
     */
    protected $triggerCntroller;

    /**
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $orderTransfer;

    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->haveTestStatemachine([static::OMS_ACTIVE_PROCESS]);
        $this->triggerCntroller = $this->tester->createTriggerController();
        $this->omsFacade = $this->tester->getOmsFacade();
        $this->salesFacade = $this->tester->getSalesFacade();

        $this->orderTransfer = $this->tester->haveOrderTransfer([
            'unitPrice' => 100,
            'sumPrice' => 100,
        ], static::OMS_ACTIVE_PROCESS);
    }

    /**
     * @return void
     */
    public function testTriggerEventForOrderItemsActionWillNotChangeItemStateOnIncorrectHttpMethod(): void
    {
        // Arrange
        $itemTransfer = $this->orderTransfer->getItems()[0];
        $request = $this->arrangeItemsTriggerRequest($itemTransfer, Request::METHOD_GET, true);
        $initialState = $itemTransfer->getState()->getName();

        // Act
        $response = $this->triggerCntroller->triggerEventForOrderItemsAction($request);
        $latestItemTransfer = $this->getItemLatestData();

        //Assert
        $this->doCommonAssertions($response);
        $this->assertEquals($initialState, $latestItemTransfer->getState()->getName());
    }

    /**
     * @return void
     */
    public function testTriggerEventForOrderItemsActionWillNotChangeItemStateWithoutCsrfTokenProvided(): void
    {
        // Arrange
        $itemTransfer = $this->orderTransfer->getItems()[0];
        $request = $this->arrangeItemsTriggerRequest($itemTransfer, Request::METHOD_POST, false);
        $initialState = $itemTransfer->getState()->getName();

        // Act
        $response = $this->triggerCntroller->triggerEventForOrderItemsAction($request);
        $latestItemTransfer = $this->getItemLatestData();

        //Assert
        $this->doCommonAssertions($response);
        $this->assertEquals($initialState, $latestItemTransfer->getState()->getName());
    }

    /**
     * @return void
     */
    public function testTriggerEventForOrderItemsActionWillChangeItemStateOnCorrectRequest(): void
    {
        // Arrange
        $itemTransfer = $this->orderTransfer->getItems()[0];
        $request = $this->arrangeItemsTriggerRequest($itemTransfer, Request::METHOD_POST, true);
        $initialState = $itemTransfer->getState()->getName();

        // Act
        $response = $this->triggerCntroller->triggerEventForOrderItemsAction($request);
        $latestItemTransfer = $this->getItemLatestData();

        //Assert
        $this->doCommonAssertions($response);
        $this->assertNotEquals($initialState, $latestItemTransfer->getState()->getName());
    }

    /**
     * @return void
     */
    public function testTriggerEventForOrderActionWillNotChangeItemsStateOnIncorrectHttpMethod(): void
    {
        // Arrange
        $request = $this->arrangeOrderTriggerRequest(Request::METHOD_GET, true);
        $initialState = $this->orderTransfer->getItems()[0]->getState()->getName();

        // Act
        $response = $this->triggerCntroller->triggerEventForOrderAction($request);
        $latestItemTransfer = $this->getItemLatestData();

        //Assert
        $this->doCommonAssertions($response);
        $this->assertEquals($initialState, $latestItemTransfer->getState()->getName());
    }

    /**
     * @return void
     */
    public function testTriggerEventForOrderActionWillNotChangeItemsStateWithoutCsrfTokenProvided(): void
    {
        // Arrange
        $request = $this->arrangeOrderTriggerRequest(Request::METHOD_POST, false);
        $initialState = $this->orderTransfer->getItems()[0]->getState()->getName();

        // Act
        $response = $this->triggerCntroller->triggerEventForOrderAction($request);
        $latestItemTransfer = $this->getItemLatestData();

        //Assert
        $this->doCommonAssertions($response);
        $this->assertEquals($initialState, $latestItemTransfer->getState()->getName());
    }

    /**
     * @return void
     */
    public function testTriggerEventForOrderActionWillNotChangeItemsStateOnCorrectRequest(): void
    {
        // Arrange
        $request = $this->arrangeOrderTriggerRequest(Request::METHOD_POST, true);
        $initialState = $this->orderTransfer->getItems()[0]->getState()->getName();

        // Act
        $response = $this->triggerCntroller->triggerEventForOrderAction($request);
        $latestItemTransfer = $this->getItemLatestData();

        //Assert
        $this->doCommonAssertions($response);
        $this->assertNotEquals($initialState, $latestItemTransfer->getState()->getName());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $method
     * @param bool $provideCsrf
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function arrangeItemsTriggerRequest(ItemTransfer $itemTransfer, string $method, bool $provideCsrf): Request
    {
        $event = $this->omsFacade->getManualEvents($itemTransfer->getIdSalesOrderItem())[0];

        $request = $this->createOrderItemsTriggerRequest(
            $method,
            $event,
            $itemTransfer->getIdSalesOrderItem(),
            $provideCsrf
        );

        return $request;
    }

    /**
     * @param string $method
     * @param bool $provideCsrf
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function arrangeOrderTriggerRequest(string $method, bool $provideCsrf): Request
    {
        $itemTransfer = $this->orderTransfer->getItems()[0];
        $initialState = $itemTransfer->getState()->getName();
        $event = $this->omsFacade->getManualEvents($itemTransfer->getIdSalesOrderItem())[0];

        $request = $this->createOrderTriggerRequest(
            $method,
            $event,
            $provideCsrf
        );

        return $request;
    }

    /**
     * @param string $method
     * @param string $event
     * @param int $idSalesOrder
     * @param bool $provideToken
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function createOrderItemsTriggerRequest(string $method, string $event, int $idSalesOrder, bool $provideToken): Request
    {
        $request = new Request();
        $request->setMethod($method);
        $request->query->set(static::QUERY_PARAM_ID_SALES_ORDER_ITEM, $idSalesOrder);
        $request->query->set(static::QUERY_PARAM_EVENT, $event);
        $request->query->set(static::QUERY_PARAM_REDIRECT, static::VALUE_QUERY_PARAM_REDIRECT);

        $formData = [];
        $form = $this->tester->createOmsTriggerForm();

        if ($provideToken === true) {
            $formData[static::QUERY_PARAM_TOKEN] = $form->createView()->offsetGet(static::QUERY_PARAM_TOKEN)->vars['value'];
        }

        $request->request->set($form->getName(), $formData);

        return $request;
    }

    /**
     * @param string $method
     * @param string $event
     * @param bool $provideToken
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function createOrderTriggerRequest(string $method, string $event, bool $provideToken): Request
    {
        $request = new Request();
        $request->setMethod($method);
        $request->query->set(static::QUERY_PARAM_ID_SALES_ORDER, $this->orderTransfer->getIdSalesOrder());
        $request->query->set(static::QUERY_PARAM_EVENT, $event);
        $request->query->set(static::QUERY_PARAM_REDIRECT, static::VALUE_QUERY_PARAM_REDIRECT);

        $formData = [];
        $form = $this->tester->createOmsTriggerForm();

        if ($provideToken === true) {
            $formData[static::QUERY_PARAM_TOKEN] = $form->createView()->offsetGet(static::QUERY_PARAM_TOKEN)->vars['value'];
        }

        $request->request->set($form->getName(), $formData);

        return $request;
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getItemLatestData(): ItemTransfer
    {
        return $this->salesFacade->findOrderByIdSalesOrder(
            $this->orderTransfer->getIdSalesOrder()
        )->getItems()[0];
    }

    /**
     * @param mixed $response
     *
     * @return void
     */
    protected function doCommonAssertions($response): void
    {
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(static::VALUE_QUERY_PARAM_REDIRECT, $response->getTargetUrl());
    }
}
