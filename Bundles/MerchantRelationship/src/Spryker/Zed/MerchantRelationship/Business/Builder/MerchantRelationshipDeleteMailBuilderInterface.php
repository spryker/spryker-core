<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Builder;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantRelationshipDeleteMailBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param list<string> $assigneeCompanyBusinessUnitEmails
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function createMailTransfer(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantTransfer $merchantTransfer,
        array $assigneeCompanyBusinessUnitEmails
    ): MailTransfer;
}
