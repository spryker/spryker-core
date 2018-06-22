<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

interface VersionRollbackInterface
{
    /**
     * @param int $idCmsPage
     * @param int $version
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function rollback($idCmsPage, $version);

    /**
     * @param int $idCmsPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return void
     */
    public function revert($idCmsPage);
}
