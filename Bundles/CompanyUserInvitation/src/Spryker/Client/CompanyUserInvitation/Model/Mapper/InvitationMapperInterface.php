<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserInvitation\Model\Mapper;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Iterator;

interface InvitationMapperInterface
{
    /**
     * @param \Iterator $invitations
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function mapInvitations(Iterator $invitations): CompanyUserInvitationCollectionTransfer;
}
