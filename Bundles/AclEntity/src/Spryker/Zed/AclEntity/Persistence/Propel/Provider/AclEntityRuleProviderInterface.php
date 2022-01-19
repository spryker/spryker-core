<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Provider;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;

interface AclEntityRuleProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function getCurrentUserAclEntityRules(): AclEntityRuleCollectionTransfer;
}
