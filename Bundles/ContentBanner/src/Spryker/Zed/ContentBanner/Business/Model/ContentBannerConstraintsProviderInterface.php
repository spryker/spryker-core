<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBanner\Business\Model;

interface ContentBannerConstraintsProviderInterface
{
    /**
     * @return array
     */
    public function getConstraintsMap(): array;
}
