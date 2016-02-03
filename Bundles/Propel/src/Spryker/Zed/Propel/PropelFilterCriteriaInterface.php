<?php
/**
 * (c) Spryker Systems GmbH copyright protected
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
