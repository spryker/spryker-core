<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\ResultFormatter\Decorator;

use Spryker\Client\Search\Model\ResultFormatter\ResultFormatterInterface;

abstract class AbstractResultFormatterDecorator implements ResultFormatterInterface
{

    /**
     * @var \Spryker\Client\Search\Model\ResultFormatter\ResultFormatterInterface
     */
    protected $resultFormatter;

    /**
     * @param \Spryker\Client\Search\Model\ResultFormatter\ResultFormatterInterface $resultFormatter
     */
    public function __construct(ResultFormatterInterface $resultFormatter)
    {
        $this->resultFormatter = $resultFormatter;
    }

}
