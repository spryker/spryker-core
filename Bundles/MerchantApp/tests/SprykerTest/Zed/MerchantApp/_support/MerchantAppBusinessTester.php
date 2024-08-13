<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\MerchantApp;

use Codeception\Actor;
use Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer;

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
 * @method \Spryker\Zed\MerchantApp\Business\MerchantAppFacadeInterface getFacade()
 */
class MerchantAppBusinessTester extends Actor
{
    use _generated\MerchantAppBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer $merchantAppOnboardingCollectionTransfer
     * @param string $type
     * @param int $expectedCount
     *
     * @return void
     */
    public function assertMerchantAppOnboardingCountByType(
        MerchantAppOnboardingCollectionTransfer $merchantAppOnboardingCollectionTransfer,
        string $type,
        int $expectedCount
    ): void {
        $merchantAppOnboardDetailsTransfer = [];

        foreach ($merchantAppOnboardingCollectionTransfer->getOnboardings() as $merchantAppOnboardingTransfer) {
            if ($merchantAppOnboardingTransfer->getType() === $type) {
                $merchantAppOnboardDetailsTransfer[] = $merchantAppOnboardingTransfer;
            }
        }

        $this->assertCount($expectedCount, $merchantAppOnboardDetailsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer $merchantAppOnboardingCollectionTransfer
     * @param string $appIdentifier
     * @param int $expectedCount
     *
     * @return void
     */
    public function assertMerchantAppOnboardingCountByAppIdentifier(
        MerchantAppOnboardingCollectionTransfer $merchantAppOnboardingCollectionTransfer,
        string $appIdentifier,
        int $expectedCount
    ): void {
        $merchantAppOnboardDetailsTransfer = [];

        foreach ($merchantAppOnboardingCollectionTransfer->getOnboardings() as $merchantAppOnboardingTransfer) {
            if ($merchantAppOnboardingTransfer->getAppIdentifier() === $appIdentifier) {
                $merchantAppOnboardDetailsTransfer[] = $merchantAppOnboardingTransfer;
            }
        }

        $this->assertCount($expectedCount, $merchantAppOnboardDetailsTransfer);
    }
}
