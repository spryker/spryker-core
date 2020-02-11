<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business\Reader;

interface CmsSlotTemplateConditionReaderInterface
{
    /**
     * @param string $templatePath
     *
     * @return string[]
     */
    public function getTemplateConditionsByPath(string $templatePath): array;
}
