<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventJournal\Model\Collector;

/**
 * @deprecated Use Log bundle instead
 */
abstract class AbstractDataCollector implements DataCollectorInterface
{

    const TYPE = 'undefined';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }

}
