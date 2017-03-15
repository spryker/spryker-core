<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache\Business\Model;

interface CacheClearerInterface
{

    /**
     * @return string[]
     */
    public function clearCache();

    /**
     * @return string[]
     */
    public function clearAutoLoaderCache();

}
