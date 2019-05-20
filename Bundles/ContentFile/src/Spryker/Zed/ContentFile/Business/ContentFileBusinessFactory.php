<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFile\Business;

use Spryker\Zed\ContentFile\Business\Validator\ContentFileListValidator;
use Spryker\Zed\ContentFile\Business\Validator\ContentFileListValidatorInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ContentFile\ContentFileConfig getConfig()
 */
class ContentFileBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ContentFile\Business\Validator\ContentFileListValidatorInterface
     */
    public function createContentFileListValidator(): ContentFileListValidatorInterface
    {
        return new ContentFileListValidator($this->getConfig());
    }
}
