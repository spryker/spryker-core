<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AuthRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CreateAccessTokenPreCheckResultTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;

interface CreateAccessTokenPreCheckPluginInterface
{
    public function preCheck(OauthRequestTransfer $oauthRequestTransfer, CreateAccessTokenPreCheckResultTransfer $result): CreateAccessTokenPreCheckResultTransfer;
}
