<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CompanyUserInvitationConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getCompanyUserInvitationStatusKeys(): array
    {
        return [
            CompanyUserInvitationConstants::INVITATION_STATUS_NEW,
            CompanyUserInvitationConstants::INVITATION_STATUS_PENDING,
            CompanyUserInvitationConstants::INVITATION_STATUS_ACCEPTED,
            CompanyUserInvitationConstants::INVITATION_STATUS_DELETED,
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
