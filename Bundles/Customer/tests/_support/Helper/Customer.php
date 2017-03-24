<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Customer\Helper;

use Codeception\Module;
use Codeception\Util\Stub;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use Testify\Module\BusinessLocator;

class Customer extends Module
{

    /**
     * @return void
     */
    public function haveCustomer()
    {
        echo '<pre>' . PHP_EOL . \Symfony\Component\VarDumper\VarDumper::dump($this->getCustomerFacade()) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();
    }

    private function getCustomerFacade()
    {
        $locator = $this->getLocator();

        $mailStub = Stub::make(MailFacadeInterface::class);
        $locator->setDependency(CustomerDependencyProvider::FACADE_MAIL, $mailStub);

        return $locator->getLocator()->customer()->facade();
    }

    /**
     * @return BusinessLocator
     */
    private function getLocator()
    {
        return $this->getModule(BusinessLocator::class);
    }
}
