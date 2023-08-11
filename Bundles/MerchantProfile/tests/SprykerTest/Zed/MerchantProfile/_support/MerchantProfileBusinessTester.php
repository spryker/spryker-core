<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfile;

use Codeception\Actor;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\MerchantProfile\Business\MerchantProfileFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\MerchantProfile\PHPMD)
 */
class MerchantProfileBusinessTester extends Actor
{
    use _generated\MerchantProfileBusinessTesterActions;

    /**
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function haveMerchantWithProfile(): MerchantTransfer
    {
        $merchantTransfer = $this->haveMerchant();
        $merchantTransfer->setMerchantProfile($this->haveMerchantProfile($merchantTransfer));

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $expectedMerchantProfile
     * @param \Generated\Shared\Transfer\MerchantTransfer $actualMerchantTransfer
     *
     * @return void
     */
    public function assertMerchantHasExactMerchantProfile(
        MerchantProfileTransfer $expectedMerchantProfile,
        MerchantTransfer $actualMerchantTransfer
    ): void {
        $this->assertInstanceOf(MerchantProfileTransfer::class, $actualMerchantTransfer->getMerchantProfile());
        $this->assertEquals(
            $expectedMerchantProfile->getIdMerchantProfile(),
            $actualMerchantTransfer->getMerchantProfile()->getIdMerchantProfile(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $actualMerchantTransfer
     *
     * @return void
     */
    public function assertMerchantHasNotMerchantProfile(MerchantTransfer $actualMerchantTransfer): void
    {
        $this->assertNull($actualMerchantTransfer->getMerchantProfile());
    }
}
