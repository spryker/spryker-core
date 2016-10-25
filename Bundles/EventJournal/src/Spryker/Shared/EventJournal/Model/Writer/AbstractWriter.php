<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventJournal\Model\Writer;

use Spryker\Shared\EventJournal\EventJournalConstants;

/**
 * @deprecated Use Log bundle instead
 */
abstract class AbstractWriter implements WriterInterface
{

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
        return EventJournalConstants::TYPE;
    }

}
