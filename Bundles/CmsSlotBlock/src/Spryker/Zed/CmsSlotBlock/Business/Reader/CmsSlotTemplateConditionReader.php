<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business\Reader;

use Spryker\Zed\CmsSlotBlock\CmsSlotBlockConfig;

class CmsSlotTemplateConditionReader implements CmsSlotTemplateConditionReaderInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlock\CmsSlotBlockConfig
     */
    protected $cmsSlotBlockConfig;

    /**
     * @param \Spryker\Zed\CmsSlotBlock\CmsSlotBlockConfig $cmsSlotBlockConfig
     */
    public function __construct(CmsSlotBlockConfig $cmsSlotBlockConfig)
    {
        $this->cmsSlotBlockConfig = $cmsSlotBlockConfig;
    }

    /**
     * @param string $templatePath
     *
     * @return string[]
     */
    public function getTemplateConditionsByPath(string $templatePath): array
    {
        $templateConditionsAssignment = $this->cmsSlotBlockConfig->getTemplateConditionsAssignment();

        if (!isset($templateConditionsAssignment[$templatePath])) {
            return [];
        }

        return $templateConditionsAssignment[$templatePath];
    }
}
