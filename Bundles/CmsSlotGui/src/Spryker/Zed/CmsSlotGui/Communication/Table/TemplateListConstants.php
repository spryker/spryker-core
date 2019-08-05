<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui\Communication\Table;

use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTemplateTableMap;

interface TemplateListConstants
{
    public const COL_ID = SpyCmsSlotTemplateTableMap::COL_ID_CMS_SLOT_TEMPLATE;
    public const COL_NAME = SpyCmsSlotTemplateTableMap::COL_NAME;
    public const COL_DESCRIPTION = SpyCmsSlotTemplateTableMap::COL_DESCRIPTION;
}
