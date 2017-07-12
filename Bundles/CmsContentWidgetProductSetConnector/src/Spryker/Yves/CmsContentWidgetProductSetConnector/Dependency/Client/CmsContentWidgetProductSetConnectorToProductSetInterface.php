<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductSetConnector\Dependency\Client;

interface CmsContentWidgetProductSetConnectorToProductSetInterface
{

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer|null
     */
    public function findProductSetByIdProductSet($idProductAbstract);

}
