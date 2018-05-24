<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CompanyUserInvitation;

class CompanyUserInvitationConfig
{
    public const INVITATION_STATUS_DELETED = 'deleted';
    public const INVITATION_STATUS_NEW = 'new';
    public const INVITATION_STATUS_ACCEPTED = 'accepted';
    public const INVITATION_STATUS_PENDING = 'pending';

    public const ROUTE_INVITATION_ACCEPT = 'invitation/accept';
    public const INVITATION_HASH = 'hash';
}
