<?php
/**
 * Created by PhpStorm.
 * User: oli
 * Date: 8/21/15
 * Time: 2:15 PM
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
