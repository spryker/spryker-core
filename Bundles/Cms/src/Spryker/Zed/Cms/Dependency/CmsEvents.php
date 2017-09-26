<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Dependency;

interface CmsEvents
{

    const CMS_PAGE_PUBLISH = 'Cms.page.publish';
    const CMS_PAGE_UNPUBLISH = 'Cms.page.unpublish';

    const ENTITY_SPY_CMS_PAGE_CREATE = 'Entity.spy_cms_page.create';
    const ENTITY_SPY_CMS_PAGE_UPDATE = 'Entity.spy_cms_page.update';
    const ENTITY_SPY_CMS_PAGE_DELETE = 'Entity.spy_cms_page.delete';

    const ENTITY_SPY_CMS_VERSION_CREATE = 'Entity.spy_cms_version.create';

}
