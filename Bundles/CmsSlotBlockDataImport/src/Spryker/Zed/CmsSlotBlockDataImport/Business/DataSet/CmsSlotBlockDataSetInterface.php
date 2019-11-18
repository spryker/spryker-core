<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet;

interface CmsSlotBlockDataSetInterface
{
    public const CMS_SLOT_ID = 'slot_id';
    public const CMS_SLOT_KEY = 'slot_key';
    public const CMS_BLOCK_ID = 'block_id';
    public const CMS_BLOCK_KEY = 'block_key';
    public const CMS_SLOT_TEMPLATE_PATH = 'template_path';
    public const CMS_SLOT_TEMPLATE_ID = 'template_id';
    public const CMS_SLOT_BLOCK_POSITION = 'position';
    public const CMS_SLOT_BLOCK_ALL_CONDITIONS = 'all_conditions';
}
