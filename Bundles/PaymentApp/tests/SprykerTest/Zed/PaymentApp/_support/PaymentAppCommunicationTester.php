<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\PaymentApp;

use Codeception\Actor;
use Codeception\Stub;
use Spryker\Zed\PaymentApp\Communication\Controller\GatewayController;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Zed\PaymentApp\Business\PaymentAppFacadeInterface getFacade(?string $moduleName = NULL)
 */
class PaymentAppCommunicationTester extends Actor
{
    use _generated\PaymentAppCommunicationTesterActions;

    /**
     * @return \Spryker\Zed\PaymentApp\Communication\Controller\GatewayController
     */
    public function getGatewayController(): GatewayController
    {
        return Stub::make(GatewayController::class, [
            'getFacade' => function () {
                return $this->getFacade();
            },
        ]);
    }
}
