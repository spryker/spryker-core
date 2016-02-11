<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Persistence\Exporter;

use Everon\Component\CriteriaBuilder\CriteriaBuilderInterface;

abstract class AbstractPdoCollectorQuery extends AbstractCollectorQuery
{

    /**
     * @var \Everon\Component\CriteriaBuilder\CriteriaBuilderInterface
     */
    protected $criteriaBuilder;

    /**
     * @return \Everon\Component\CriteriaBuilder\CriteriaBuilderInterface
     */
    public function getCriteriaBuilder()
    {
        return $this->criteriaBuilder;
    }

    /**
     * @param \Everon\Component\CriteriaBuilder\CriteriaBuilderInterface $criteriaBuilder
     *
     * @return $this
     */
    public function setCriteriaBuilder(CriteriaBuilderInterface $criteriaBuilder)
    {
        $this->criteriaBuilder = $criteriaBuilder;

        return $this;
    }

}
