<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification\MetaData;

interface MetaProviderFactoryInterface
{
    /**
     * @param string $type
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider
     */
    public function createMetaProviderByType($type);
}
