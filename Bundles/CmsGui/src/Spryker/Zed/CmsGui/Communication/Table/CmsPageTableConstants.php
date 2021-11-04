<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Table;

use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Spryker\Zed\CmsGui\Communication\Controller\CreateGlossaryController;
use Spryker\Zed\CmsGui\Communication\Controller\EditPageController;
use Spryker\Zed\CmsGui\Communication\Controller\ListPageController;
use Spryker\Zed\CmsGui\Communication\Controller\VersionPageController;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface CmsPageTableConstants
{
    /**
     * @var string
     */
    public const ACTIONS = 'Actions';

    /**
     * @var string
     */
    public const URL_CMS_PAGE_ACTIVATE = '/cms-gui/edit-page/activate';

    /**
     * @var string
     */
    public const URL_CMS_PAGE_DEACTIVATE = '/cms-gui/edit-page/deactivate';

    public const COL_ID_CMS_PAGE = SpyCmsPageTableMap::COL_ID_CMS_PAGE;
    public const COL_IS_ACTIVE = SpyCmsPageTableMap::COL_IS_ACTIVE;

    /**
     * @var string
     */
    public const COL_URL = 'Url';

    /**
     * @var string
     */
    public const COL_TEMPLATE = 'template_name';

    /**
     * @var string
     */
    public const COL_NAME = 'name';

    /**
     * @var string
     */
    public const COL_STATUS = 'status';

    /**
     * @var string
     */
    public const COL_CMS_URLS = 'cmsUrls';

    /**
     * @var string
     */
    public const COL_CMS_VERSION_COUNT = 'cmsVersionCount';

    /**
     * @var string
     */
    public const COL_STORE_RELATION = 'Store';

    public const VERSION_PAGE_URL_PARAM_ID_CMS_PAGE = VersionPageController::URL_PARAM_ID_CMS_PAGE;
    public const VERSION_PAGE_URL_PARAM_REDIRECT_URL = VersionPageController::URL_PARAM_REDIRECT_URL;

    public const LIST_PAGE_URL_PARAM_ID_CMS_PAGE = ListPageController::URL_PARAM_ID_CMS_PAGE;

    public const EDIT_PAGE_URL_PARAM_ID_CMS_PAGE = EditPageController::URL_PARAM_ID_CMS_PAGE;
    public const EDIT_PAGE_URL_PARAM_REDIRECT_URL = EditPageController::URL_PARAM_REDIRECT_URL;

    public const CREATE_GLOSSARY_URL_PARAM_ID_CMS_PAGE = CreateGlossaryController::URL_PARAM_ID_CMS_PAGE;

    public const SORT_DESC = TableConfiguration::SORT_DESC;
}
