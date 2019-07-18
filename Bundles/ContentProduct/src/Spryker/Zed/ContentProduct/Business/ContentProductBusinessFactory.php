<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProduct\Business;

use Spryker\Zed\ContentProduct\Business\Validator\ContentProductAbstractListValidator;
use Spryker\Zed\ContentProduct\Business\Validator\ContentProductAbstractListValidatorInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ContentProduct\ContentProductConfig getConfig()
 */
class ContentProductBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ContentProduct\Business\Validator\ContentProductAbstractListValidatorInterface
     */
    public function createContentProductAbstractListValidator(): ContentProductAbstractListValidatorInterface
    {
        return new ContentProductAbstractListValidator($this->getConfig());
    }
}
