<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\ResultFormatter\Decorator;

use Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter;

abstract class AbstractElasticsearchResultFormatterDecorator extends AbstractElasticsearchResultFormatter
{

    /**
     * @var \Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter
     */
    protected $resultFormatter;

    /**
     * @param \Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter $resultFormatter
     */
    public function __construct(AbstractElasticsearchResultFormatter $resultFormatter)
    {
        $this->resultFormatter = $resultFormatter;
    }

}
