<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConfig as SharedCompanyUserInvitationConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CompanyUserInvitationConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getCompanyUserInvitationStatusKeys(): array
    {
        return [
            SharedCompanyUserInvitationConfig::INVITATION_STATUS_NEW,
            SharedCompanyUserInvitationConfig::INVITATION_STATUS_PENDING,
            SharedCompanyUserInvitationConfig::INVITATION_STATUS_ACCEPTED,
            SharedCompanyUserInvitationConfig::INVITATION_STATUS_DELETED,
        ];
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->get(ApplicationConstants::BASE_URL_YVES);
    }
}
