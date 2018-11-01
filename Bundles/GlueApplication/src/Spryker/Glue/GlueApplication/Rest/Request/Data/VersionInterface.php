<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request\Data;

interface VersionInterface
{
    /**
     * @return int
     */
    public function getMajor(): int;

    /**
     * @return int
     */
    public function getMinor(): int;
}
