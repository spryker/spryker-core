<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model\BulkTouch\Filter;

use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

abstract class AbstractIdFilter implements FilterInterface
{

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $touchQueryContainer
     */
    public function __construct(TouchQueryContainerInterface $touchQueryContainer)
    {
        $this->touchQueryContainer = $touchQueryContainer;
    }

}
