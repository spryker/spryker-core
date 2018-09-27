<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Formatter;

use Spryker\Shared\Money\Exception\FormatterNotFoundException;

class MoneyFormatterCollection implements MoneyFormatterCollectionInterface
{
    public const FORMATTER_WITH_SYMBOL = 'FORMATTER_WITH_CURRENCY';
    public const FORMATTER_WITHOUT_SYMBOL = 'FORMATTER_WITHOUT_CURRENCY';

    /**
     * @var array
     */
    protected $formatter;

    /**
     * @param \Spryker\Shared\Money\Formatter\MoneyFormatterInterface $formatter
     * @param string $type
     *
     * @return $this
     */
    public function addFormatter(MoneyFormatterInterface $formatter, $type)
    {
        $this->formatter[$type] = $formatter;

        return $this;
    }

    /**
     * @param string $type
     *
     * @throws \Spryker\Shared\Money\Exception\FormatterNotFoundException
     *
     * @return \Spryker\Shared\Money\Formatter\MoneyFormatterInterface
     */
    public function getFormatter($type)
    {
        if (isset($this->formatter[$type])) {
            return $this->formatter[$type];
        }

        throw new FormatterNotFoundException(
            sprintf(
                'Could not find a formatter by type "%s". Maybe type is misspelled or type was not added?',
                $type
            )
        );
    }
}
