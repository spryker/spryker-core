<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Braintree\Business;

use Functional\Spryker\Zed\Braintree\Business\Api\Adapter\Http\CaptureAdapterMock;
use Functional\Spryker\Zed\Braintree\Business\Api\Adapter\Http\PreAuthorizationAdapterMock;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLog;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLog;

/**
 * @group Zed
 * @group Business
 * @group Braintree
 * @group BraintreeFacadeConditionsTest
 */
class BraintreeFacadeCommandsTest extends AbstractFacadeTest
{

    /**
     * @var SpyPaymentBraintreeTransactionStatusLog
     */
    protected $transactionStatusLogEntity;

    /**
     * @var SpyPaymentBraintreeTransactionRequestLog
     */
    protected $transactionRequestLogEntity;

    /**
     * @return void
     */
    public function _testAuthorize()
    {

        $orderTransfer = $this->createOrderTransfer();
        $facade = $this->getFacade();
        $response = $facade->isAuthorizationApproved($orderTransfer);
        $this->assertTrue($response);
    }

}
