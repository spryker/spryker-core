<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model\BulkTouch\Handler;

use Spryker\Zed\Touch\Business\Model\BulkTouch\BulkTouchInterface;
use Spryker\Zed\Touch\Business\Model\BulkTouch\Filter\FilterInterface;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

abstract class AbstractBulkTouchHandler implements BulkTouchInterface
{
    public const BULK_UPDATE_CHUNK_SIZE = 250;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var \Spryker\Zed\Touch\Business\Model\BulkTouch\Filter\FilterInterface
     */
    protected $filter;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $touchQueryContainer
     * @param \Spryker\Zed\Touch\Business\Model\BulkTouch\Filter\FilterInterface $filter
     */
    public function __construct(TouchQueryContainerInterface $touchQueryContainer, FilterInterface $filter)
    {
        $this->touchQueryContainer = $touchQueryContainer;
        $this->filter = $filter;
    }
}
