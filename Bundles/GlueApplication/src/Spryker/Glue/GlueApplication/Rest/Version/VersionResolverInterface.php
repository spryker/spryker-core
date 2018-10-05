<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Version;

use Generated\Shared\Transfer\RestVersionTransfer;
use Symfony\Component\HttpFoundation\Request;

interface VersionResolverInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestVersionTransfer
     */
    public function findVersion(Request $request): RestVersionTransfer;
}
