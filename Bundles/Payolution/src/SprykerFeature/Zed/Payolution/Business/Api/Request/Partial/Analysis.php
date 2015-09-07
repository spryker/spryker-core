<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request\Partial;


use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequest;

class Analysis extends AbstractRequest
{
    /**
     * @var Criterion
     */
    protected $criterion;

    /**
     * @return Criterion
     */
    public function getCriterion()
    {
        return $this->criterion;
    }

    /**
     * @param Criterion $criterion
     */
    public function setCriterion($criterion)
    {
        $this->criterion = $criterion;
    }

}
