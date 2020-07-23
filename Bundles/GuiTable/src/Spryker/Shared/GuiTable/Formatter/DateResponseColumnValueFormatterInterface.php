<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Formatter;

interface DateResponseColumnValueFormatterInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function formatColumnValue($value);
}
