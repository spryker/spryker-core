<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\UrlStorage\Dependency\Plugin;

use Generated\Shared\Transfer\SpyUrlEntityTransfer;

interface UrlStorageResourceMapperPluginInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyUrlEntityTransfer $spyUrlEntityTransfer
     *
     * @return \Generated\Shared\Transfer\UrlStorageResourceMapTransfer
     */
    public function map(SpyUrlEntityTransfer $spyUrlEntityTransfer, $options = []);

}
