<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantPortalGui\Communication\Validator;

use Generated\Shared\Transfer\ValidationResponseTransfer;

interface CsrfTokenValidatorInterface
{
    /**
     * @param string $tokenId
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validate(string $tokenId, string $value): ValidationResponseTransfer;
}
