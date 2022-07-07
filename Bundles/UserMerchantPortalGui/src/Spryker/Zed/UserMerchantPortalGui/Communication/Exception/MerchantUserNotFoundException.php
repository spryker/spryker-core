<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Exception;

use Exception;

class MerchantUserNotFoundException extends Exception
{
    /**
     * @param int $idUser
     */
    public function __construct(int $idUser)
    {
        parent::__construct($this->buildMessage($idUser));
    }

    /**
     * @param int $idUser
     *
     * @return string
     */
    protected function buildMessage(int $idUser): string
    {
        return sprintf(
            'Merchant user not found for idUser %d.',
            $idUser,
        );
    }
}
