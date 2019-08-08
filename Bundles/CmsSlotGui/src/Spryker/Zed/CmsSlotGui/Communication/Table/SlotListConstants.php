<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui\Communication\Table;

use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTableMap;

interface SlotListConstants
{
    public const COL_KEY = SpyCmsSlotTableMap::COL_KEY;
    public const COL_NAME = SpyCmsSlotTableMap::COL_NAME;
    public const COL_DESCRIPTION = SpyCmsSlotTableMap::COL_DESCRIPTION;
    public const COL_OWNERSHIP = SpyCmsSlotTableMap::COL_CONTENT_PROVIDER_TYPE;
    public const COL_STATUS = SpyCmsSlotTableMap::COL_IS_ACTIVE;
    public const COL_ACTIONS = 'actions';
    public const BASE_URL = '/cms-slot-gui/slot-list/';
    public const TABLE_CLASS = 'cms-slot-list-table';
    public const CLASS_ACTIVATE_BUTTON = 'btn-view slot-activation';
    public const CLASS_DEACTIVATE_BUTTON = 'btn-danger slot-activation';
    public const URL_ACTIVATE_BUTTON = '/cms-slot-gui/activate-slot/activate';
    public const URL_DEACTIVATE_BUTTON = '/cms-slot-gui/activate-slot/deactivate';
}
