<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\ResultFormatter;

interface ResultFormatterInterface
{
    /**
     * Formats a set of records.
     *
     * @param array $records A set of records to format
     *
     * @return mixed The formatted set of records
     */
    public function formatBulk(array $records);
}
