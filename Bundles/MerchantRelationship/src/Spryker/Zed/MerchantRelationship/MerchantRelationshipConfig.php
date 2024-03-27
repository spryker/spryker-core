<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship;

use Spryker\Shared\MerchantRelationship\MerchantRelationshipConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantRelationshipConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return int
     */
    public function getDefaultPaginationLimit(): int
    {
        return 20;
    }

    /**
     * Specification:
     * - Returns base URL for Yves including scheme and port (e.g. http://www.de.demoshop.local:8080).
     *
     * @api
     *
     * @return string
     */
    public function getYvesBaseUrl(): string
    {
        return $this->get(MerchantRelationshipConstants::BASE_URL_YVES);
    }
}
