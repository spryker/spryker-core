<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthCustomerConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\OauthCustomerConnector\Business\OauthCustomerConnectorFacadeInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group OauthCustomerConnector
 * @group Business
 * @group Facade
 * @group OauthCustomerConnectorFacadeTest
 * Add your own group annotations below this line
 */
class OauthCustomerConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\OauthCustomerConnector\OauthCustomerConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCustomerShouldReturnCustomerWhenCredentialsMatch(): void
    {
        $oauthUserTransfer = new OauthUserTransfer();
        $oauthUserTransfer->setUsername('spencor.hopkin@spryker.com')
            ->setPassword('change123');

        $oauthUserTransfer = $this->getOauthCustomerConnectorFacade()->getCustomer($oauthUserTransfer);

        $this->assertTrue($oauthUserTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testGetCustomerShouldReturnFailureCustomerWhenCredentialsNotMatch(): void
    {
        $oauthUserTransfer = new OauthUserTransfer();
        $oauthUserTransfer->setUsername('spencor.hopkin@spryker.com')
            ->setPassword('change1233');

        $oauthUserTransfer = $this->getOauthCustomerConnectorFacade()->getCustomer($oauthUserTransfer);

        $this->assertFalse($oauthUserTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testGetScopesShouldReturnScopeListForRequest(): void
    {
        $oauthScopeRequestTransfer = new OauthScopeRequestTransfer();

        $scopes = $this->getOauthCustomerConnectorFacade()->getScopes($oauthScopeRequestTransfer);

        $this->assertNotEmpty($scopes);
    }

    /**
     * @return \Spryker\Zed\OauthCustomerConnector\Business\OauthCustomerConnectorFacadeInterface
     */
    protected function getOauthCustomerConnectorFacade(): OauthCustomerConnectorFacadeInterface
    {
        return $this->tester->getLocator()->oauthCustomerConnector()->facade();
    }
}
