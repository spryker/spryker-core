<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductRelationConfig extends AbstractBundleConfig
{
    /**
     * @return string|null
     */
    public function findYvesHost()
    {
        $config = $this->getConfig();

        if ($config->hasKey(ApplicationConstants::BASE_URL_YVES)) {
            return $config->get(ApplicationConstants::BASE_URL_YVES);
        }

        // @deprecated This is just for backward compatibility
        if ($config->hasKey(ApplicationConstants::HOST_YVES)) {
            return $config->get(ApplicationConstants::HOST_YVES);
        }

        return null;
    }
}
