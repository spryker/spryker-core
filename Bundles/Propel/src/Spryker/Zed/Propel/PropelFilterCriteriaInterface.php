<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel;

use Generated\Shared\Transfer\FilterTransfer;

interface PropelFilterCriteriaInterface
{

    /**
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    public function getFilterTransfer();

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     */
    public function setFilterTransfer(FilterTransfer $filterTransfer);

    /**
     * @return \Propel\Runtime\ActiveQuery\Criteria
     */
    public function toCriteria();

}
