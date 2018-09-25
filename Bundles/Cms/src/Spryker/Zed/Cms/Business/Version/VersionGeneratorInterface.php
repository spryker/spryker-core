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
    public function generateNewCmsVersion(int $idCmsPage): int;

    /**
     * @param int $versionNumber
     *
     * @return string
     */
    public function generateNewCmsVersionName(int $versionNumber): string;

    /**
     * @param int $versionNumber
     *
     * @return string
     */
    public function generateReferenceCmsVersionName(int $versionNumber): string;
}
