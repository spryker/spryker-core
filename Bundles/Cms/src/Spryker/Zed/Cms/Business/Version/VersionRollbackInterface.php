<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;

interface VersionRollbackInterface
{
    /**
     * @param int $idCmsPage
     * @param int $version
     *
     * @throws MissingPageException
     *
     * @return CmsVersionTransfer
     */
    public function rollback($idCmsPage, $version);

    /**
     * @param int $idCmsPage
     *
     * @throws MissingPageException
     *
     * @return bool
     */
    public function revert($idCmsPage);
}
