<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Table;

use Orm\Zed\Content\Persistence\Map\SpyContentTableMap;

interface ContentTableConstants
{
    public const COL_ID_CONTENT = SpyContentTableMap::COL_ID_CONTENT;
    public const COL_TERM_KEY = SpyContentTableMap::COL_CONTENT_TERM_KEY;
    public const COL_NAME = SpyContentTableMap::COL_NAME;
    public const COL_DESCRIPTION = SpyContentTableMap::COL_DESCRIPTION;
    public const COL_CONTENT_TYPE_KEY = SpyContentTableMap::COL_CONTENT_TYPE_KEY;
    public const COL_UPDATED_AT = SpyContentTableMap::COL_UPDATED_AT;
    public const COL_CREATED_AT = SpyContentTableMap::COL_CREATED_AT;
    public const COL_KEY = SpyContentTableMap::COL_KEY;

    public const REQUEST_ID_CONTENT = 'id-content';
    public const REQUEST_TERM_KEY = 'term-key';
    public const URL_CONTENT_EDIT = '/content-gui/edit-content';
    public const COL_ACTIONS = 'actions';
}
