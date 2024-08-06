<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding;

use Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer;

interface MerchantAppOnboardingStatusInterface
{
    /**
     * @var string
     */
    public const INCOMPLETE = 'incomplete';

    /**
     * @var string
     */
    public const COMPLETED = 'completed';

    /**
     * @var string
     */
    public const ENABLED = 'enabled';

    /**
     * @var string
     */
    public const RESTRICTED = 'restricted';

    /**
     * @var string
     */
    public const RESTRICTED_SOON = 'restricted soon';

    /**
     * @var string
     */
    public const PENDING = 'pending';

    /**
     * @var string
     */
    public const REJECTED = 'rejected';

    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer $merchantAppOnboardingStatusTransfer
     *
     * @return void
     */
    public function updateMerchantAppOnboardingStatus(MerchantAppOnboardingStatusTransfer $merchantAppOnboardingStatusTransfer): void;
}
