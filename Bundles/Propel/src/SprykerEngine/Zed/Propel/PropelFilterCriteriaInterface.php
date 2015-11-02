<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel;

use Propel\Runtime\ActiveQuery\Criteria;
use Generated\Shared\Transfer\FilterTransfer;

interface PropelFilterCriteriaInterface
{

    /**
     * @return FilterTransfer
     */
    public function getFilterTransfer();

    /**
     * @param FilterTransfer $filterTransfer
     */
    public function setFilterTransfer(FilterTransfer $filterTransfer);

    /**
     * @return Criteria
     */
    public function toCriteria();

}
