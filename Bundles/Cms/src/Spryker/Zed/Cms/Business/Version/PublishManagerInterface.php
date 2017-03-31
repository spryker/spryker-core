<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Generated\Shared\Transfer\CmsPageVersionTransfer;

interface PublishManagerInterface
{

    /**
     * @param int $idCmsPage
     *
     * @return CmsPageVersionTransfer
     */
    public function publishAndVersionCmsPage($idCmsPage);

}
