<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Communication\Reader;

use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Symfony\Component\HttpFoundation\Request;

interface ResourceOwnerReaderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ResourceOwnerTransfer|null
     */
    public function getResourceOwner(Request $request): ?ResourceOwnerTransfer;
}
