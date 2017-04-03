<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

interface RevertManagerInterface
{

    /**
     * @param int $idCmsVersionOrigin
     * @param int $idCmsVersionTarget
     *
     * @return bool
     */
    public function revertCmsVersion($idCmsVersionOrigin, $idCmsVersionTarget);
}
