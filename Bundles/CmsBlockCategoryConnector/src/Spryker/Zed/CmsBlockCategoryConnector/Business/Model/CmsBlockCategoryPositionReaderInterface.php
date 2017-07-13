<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Business\Model;

interface CmsBlockCategoryPositionReaderInterface
{

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\CmsBlockCategoryPositionTransfer|null
     */
    public function findCmsBlockCategoryPositionByName($name);

}
