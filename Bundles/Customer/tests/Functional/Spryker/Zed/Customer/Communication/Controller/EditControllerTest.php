<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Customer\Communication\Controller;

use Codeception\TestCase\Test;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\Communication\Controller\EditController;
use Spryker\Zed\Customer\Communication\CustomerCommunicationFactory;
use Spryker\Zed\Customer\Communication\Form\CustomerForm;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Customer
 * @group Communication
 * @group Controller
 * @group EditControllerTest
 */
class EditControllerTest extends Test
{

    const NEW_FIRST_NAME = 'superMan';

    /**
     * @var \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    private $customer;

    /**
     * @var \Spryker\Zed\Customer\Communication\Controller\EditController
     */
    private $controller;

    /**
     * @return void
     */
    public function setUp()
    {
        $customer = new SpyCustomer();
        $customer->setFirstName('firstname');
        $customer->setLastName('lastname');
        $customer->setEmail('cat@face.com');
        $customer->setCustomerReference('reference');
        $customer->save();

        $this->customer = $customer;

        $customerBusinessFactory = $this->getBusinessFactory();
        $customerFacade = new CustomerFacade();
        $customerFacade->setFactory($customerBusinessFactory);

        $controllerMock = $this->getMock(EditController::class, ['getFactory', 'getFacade']);
        $controllerMock->method('getFactory')->willReturn(new CustomerCommunicationFactory());
        $controllerMock->method('getFacade')->willReturn($customerFacade);

        $this->controller = $controllerMock;
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerBusinessFactory
     */
    protected function getBusinessFactory()
    {
        $customerBusinessFactory = new CustomerBusinessFactory();
        $customerBusinessFactory->setContainer($this->getContainer());

        return $customerBusinessFactory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainer()
    {
        $dependencyProvider = new CustomerDependencyProvider();
        $container = new Container();

        $dependencyProvider->provideBusinessLayerDependencies($container);
        $container[CustomerDependencyProvider::SENDER_PLUGINS] = $this->getSenderPlugins();

        return $container;
    }

    /**
     * @return array
     */
    protected function getSenderPlugins()
    {
        $senderPlugins = [];

        $senderPlugins[CustomerDependencyProvider::REGISTRATION_TOKEN_SENDERS] = [];
        $senderPlugins[CustomerDependencyProvider::PASSWORD_RESTORE_TOKEN_SENDERS] = [];
        $senderPlugins[CustomerDependencyProvider::PASSWORD_RESTORED_CONFIRMATION_SENDERS] = [];

        return $senderPlugins;
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        $this->customer->delete();
    }

    /**
     * @return void
     */
    public function testIndexAction()
    {
        $request = Request::create('/customer/edit?id-customer' . $this->customer->getIdCustomer(), 'GET', ['id-customer' => $this->customer->getIdCustomer()]);
        $application = (new Pimple())->getApplication();
        $application['request'] = $request;

        $result = $this->controller->indexAction($request);

        $this->assertInternalType('array', $result);
        $this->assertSame($this->customer->getIdCustomer(), $result['idCustomer']);
        $this->assertInstanceOf(FormView::class, $result['form']);
    }

    /**
     * @return void
     */
    public function testIndexActionUpdateUser()
    {
        $customerData = $this->getFormData();
        $customerData[CustomerForm::FIELD_FIRST_NAME] = self::NEW_FIRST_NAME;
        $data = [
            'customer' => $customerData,
        ];

        $request = Request::create('/customer/edit?id-customer=' . $this->customer->getIdCustomer(), 'POST', $data);
        $application = (new Pimple())->getApplication();
        $application['request'] = $request;

        $result = $this->controller->indexAction($request);
        $this->customer->reload();

        $this->assertInstanceOf(RedirectResponse::class, $result);
        $this->assertSame(self::NEW_FIRST_NAME, $this->customer->getFirstName());
    }

    /**
     * @return array
     */
    protected function getFormData()
    {
        $request = Request::create('/customer/edit?id-customer' . $this->customer->getIdCustomer(), 'GET', ['id-customer' => $this->customer->getIdCustomer()]);
        $application = (new Pimple())->getApplication();
        $application['request'] = $request;

        $result = $this->controller->indexAction($request);

        return $this->getFormDataFromResult($result['form']);
    }

    /**
     * @param \Symfony\Component\Form\FormView $formView
     *
     * @return array
     */
    protected function getFormDataFromResult(FormView $formView)
    {
        $customerData = [];
        foreach ($formView->getIterator() as $item) {
            $customerData[$item->vars['name']] = $item->vars['value'];
        }

        return $customerData;
    }

}
