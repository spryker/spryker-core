<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

interface VersionGeneratorInterface
{
    /**
     * @param int $idCmsPage
     *
     * @return int
     */
    public function generateNewCmsVersion($idCmsPage);

    /**
     * @param int $versionNumber
     *
     * @return string
     */
    public function generateNewCmsVersionName($versionNumber);

    /**
     * @param int $versionNumber
     *
     * @return string
     */
    public function generateReferenceCmsVersionName($versionNumber);
}
