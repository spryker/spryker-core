<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Persistence\Exporter;

use Everon\Component\CriteriaBuilder\CriteriaBuilderInterface;

abstract class AbstractPdoCollectorQuery extends AbstractCollectorQuery
{

    /**
     * @var CriteriaBuilderInterface
     */
    protected $criteriaBuilder;

    /**
     * @return CriteriaBuilderInterface
     */
    public function getCriteriaBuilder()
    {
        return $this->criteriaBuilder;
    }

    /**
     * @param CriteriaBuilderInterface $criteriaBuilder
     *
     * @return self
     */
    public function setCriteriaBuilder(CriteriaBuilderInterface $criteriaBuilder)
    {
        $this->criteriaBuilder = $criteriaBuilder;

        return $this;
    }

}
