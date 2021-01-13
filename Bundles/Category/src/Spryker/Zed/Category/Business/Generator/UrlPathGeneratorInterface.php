<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Generator;

interface UrlPathGeneratorInterface
{
    /**
     * @param array $categoryPath
     *
     * @return string
     */
    public function generate(array $categoryPath): string;
}
