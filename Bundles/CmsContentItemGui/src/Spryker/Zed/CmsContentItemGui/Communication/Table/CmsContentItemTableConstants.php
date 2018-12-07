<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentItemGui\Communication\Table;

use Orm\Zed\CmsContentPlan\Persistence\Map\SpyCmsContentPlanTableMap;

interface CmsContentItemTableConstants
{
    public const COL_ID_CMS_CONTENT_ITEM = SpyCmsContentPlanTableMap::COL_ID_CMS_CONTENT_PLAN;
    public const COL_NAME = SpyCmsContentPlanTableMap::COL_NAME;
    public const COL_DESCRIPTION = SpyCmsContentPlanTableMap::COL_DESCRIPTION;
    public const COL_TYPE = SpyCmsContentPlanTableMap::COL_TYPE;
    public const COL_UPDATED_AT = SpyCmsContentPlanTableMap::COL_UPDATED_AT;

    public const REQUEST_ID_CMS_CONTENT_ITEM = 'id-cms-content-item';
    public const URL_CMS_CONTENT_ITEM_EDIT = '/cms-content-item-gui/edit-cms-content-item-gui';
    public const COL_ACTIONS = 'actions';
}
