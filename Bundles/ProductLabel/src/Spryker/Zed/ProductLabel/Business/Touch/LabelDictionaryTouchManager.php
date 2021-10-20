<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Touch;

use Spryker\Shared\ProductLabel\ProductLabelConstants;
use Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToTouchInterface;

class LabelDictionaryTouchManager implements LabelDictionaryTouchManagerInterface
{
    /**
     * @var \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToTouchInterface $touchFacade
     */
    public function __construct(ProductLabelToTouchInterface $touchFacade)
    {
        $this->touchFacade = $touchFacade;
    }

    /**
     * @return void
     */
    public function touchActive()
    {
        $this->touchFacade->touchActive(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY,
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER,
        );
    }
}
