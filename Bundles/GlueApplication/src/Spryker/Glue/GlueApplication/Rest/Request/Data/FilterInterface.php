<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request\Data;

interface FilterInterface
{
    /**
     * @return string
     */
    public function getResource(): string;

    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @return string
     */
    public function getField(): string;
}
