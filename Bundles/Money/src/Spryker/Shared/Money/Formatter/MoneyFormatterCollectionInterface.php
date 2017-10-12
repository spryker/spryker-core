<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Formatter;

interface MoneyFormatterCollectionInterface
{
    /**
     * @param \Spryker\Shared\Money\Formatter\MoneyFormatterInterface $formatter
     * @param string $type
     *
     * @return $this
     */
    public function addFormatter(MoneyFormatterInterface $formatter, $type);

    /**
     * @param string $type
     *
     * @return \Spryker\Shared\Money\Formatter\MoneyFormatterInterface
     */
    public function getFormatter($type);
}
