<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;

interface VersionPublisherInterface
{

    /**
     * @param int $idCmsPage
     * @param string|null $versionName
     *
     * @throws MissingPageException
     *
     * @return CmsVersionTransfer
     */
    public function publishAndVersion($idCmsPage, $versionName = null);

}
