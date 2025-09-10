<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\MerchantProduct;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\EventEntityTransfer;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use Spryker\Shared\MerchantProduct\MerchantProductConfig;
use Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToEventFacadeInterface;
use Spryker\Zed\MerchantProduct\MerchantProductDependencyProvider;

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
 */
class MerchantProductCommunicationTester extends Actor
{
    use _generated\MerchantProductCommunicationTesterActions;

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function setDependencyWithExpectedCall(int $idProductAbstract): void
    {
        $eventFacadeMock = Stub::makeEmpty(MerchantProductToEventFacadeInterface::class);
        $eventFacadeMock->expects(new InvokedCount(1))->method('triggerBulk')->with(MerchantProductConfig::PRODUCT_ABSTRACT_PUBLISH, [
            (new EventEntityTransfer())
                ->setId($idProductAbstract),
        ]);

        $this->setDependency(MerchantProductDependencyProvider::FACADE_EVENT, $eventFacadeMock);
    }
}
