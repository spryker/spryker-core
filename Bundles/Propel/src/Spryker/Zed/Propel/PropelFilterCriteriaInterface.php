<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel;

use Propel\Runtime\ActiveQuery\Criteria;
use Generated\Shared\Transfer\FilterTransfer;

interface PropelFilterCriteriaInterface
{

    /**
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    public function getFilterTransfer();

    /**
     * @param FilterTransfer $filterTransfer
     */
    public function setFilterTransfer(FilterTransfer $filterTransfer);

    /**
     * @return \Propel\Runtime\ActiveQuery\Criteria
     */
    public function toCriteria();

}
