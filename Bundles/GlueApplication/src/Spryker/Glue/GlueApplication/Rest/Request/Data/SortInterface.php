<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request\Data;

interface SortInterface
{
    public const SORT_DESC = 'DESC';
    public const SORT_ASC = 'ASC';

    /**
     * @return string
     */
    public function getField(): string;

    /**
     * @return string
     */
    public function getDirection(): string;
}
