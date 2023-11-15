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

    /**
     * @var string
     */
    public const COL_ID_CMS_PAGE = SpyCmsPageTableMap::COL_ID_CMS_PAGE;

    /**
     * @var string
     */
    public const COL_IS_ACTIVE = SpyCmsPageTableMap::COL_IS_ACTIVE;

    /**
     * @uses \Orm\Zed\Url\Persistence\Map\SpyUrlTableMap::COL_FK_RESOURCE_PAGE
     *
     * @var string
     */
    public const COL_URL_TABLE_FK_RESOURCE_PAGE = 'spy_url.fk_resource_page';

    /**
     * @uses \Orm\Zed\Url\Persistence\Map\SpyUrlTableMap::COL_URL
     *
     * @var string
     */
    public const COL_URL_TABLE_URL = 'spy_url.url';

    /**
     * @uses \Orm\Zed\Cms\Persistence\Map\SpyCmsVersionTableMap::COL_FK_CMS_PAGE
     *
     * @var string
     */
    public const COL_VERSION_TABLE_FK_CMS_PAGE = 'spy_cms_version.fk_cms_page';

    /**
     * @uses \Orm\Zed\Cms\Persistence\Map\SpyCmsVersionTableMap::COL_VERSION
     *
     * @var string
     */
    public const COL_VERSION_TABLE_VERSION = 'spy_cms_version.version';

    /**
     * @uses \Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap::COL_ID_CMS_PAGE
     *
     * @var string
     */
    public const COL_CMS_PAGE_TABLE_ID_CMS_PAGE = 'spy_cms_page.id_cms_page';

    /**
     * @uses \Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap::COL_TEMPLATE_NAME
     *
     * @var string
     */
    public const COL_CMS_TEMPLATE_TABLE_TEMPLATE_NAME = 'spy_cms_template.template_name';

    /**
     * @uses \Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap::COL_NAME
     *
     * @var string
     */
    public const COL_CMS_PAGE_LOCALIZED_TABLE_ATTRIBUTES_NAME = 'spy_cms_page_localized_attributes.name';

    /**
     * @uses \Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap::COL_FK_CMS_PAGE
     *
     * @var string
     */
    public const COL_CMS_PAGE_LOCALIZED_ATTRIBUTES_TABLE_FK_CMS_PAGE = 'spy_cms_page_localized_attributes.fk_cms_page';

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
