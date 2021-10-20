<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CompanyUserInvitation;

class CompanyUserInvitationConfig
{
    /**
     * @var string
     */
    public const INVITATION_STATUS_DELETED = 'deleted';

    /**
     * @var string
     */
    public const INVITATION_STATUS_NEW = 'new';

    /**
     * @var string
     */
    public const INVITATION_STATUS_ACCEPTED = 'accepted';

    /**
     * @var string
     */
    public const INVITATION_STATUS_PENDING = 'pending';

    /**
     * @var string
     */
    public const ROUTE_INVITATION_ACCEPT = 'invitation/accept';

    /**
     * @var string
     */
    public const INVITATION_HASH = 'hash';
}
