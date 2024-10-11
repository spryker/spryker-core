<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Reader;

use Generated\Shared\Transfer\RouterActionCollectionTransfer;
use Generated\Shared\Transfer\RouterBundleCollectionTransfer;
use Generated\Shared\Transfer\RouterControllerCollectionTransfer;

interface RouterReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\RouterBundleCollectionTransfer
     */
    public function getBundleCollection(): RouterBundleCollectionTransfer;

    /**
     * @param string $bundle
     *
     * @return \Generated\Shared\Transfer\RouterControllerCollectionTransfer
     */
    public function getControllerCollection(string $bundle): RouterControllerCollectionTransfer;

    /**
     * @param string $bundle
     * @param string $controller
     *
     * @return \Generated\Shared\Transfer\RouterActionCollectionTransfer
     */
    public function getActionCollection(string $bundle, string $controller): RouterActionCollectionTransfer;
}
