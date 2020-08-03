<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Mapping;

use Elastica\Response;

/**
 * @method $this setMeta(array $meta)
 */
interface MappingAdapterInterface
{
    /**
     * @return \Elastica\Response
     */
    public function send(): Response;
}
