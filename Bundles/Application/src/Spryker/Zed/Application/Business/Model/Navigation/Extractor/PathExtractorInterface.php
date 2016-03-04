<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Business\Model\Navigation\Extractor;

interface PathExtractorInterface
{

    /**
     * @param array $menu
     *
     * @return array
     */
    public function extractPathFromMenu(array $menu);

}
