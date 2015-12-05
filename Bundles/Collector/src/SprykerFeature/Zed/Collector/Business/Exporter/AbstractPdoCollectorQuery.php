<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Everon\Component\CriteriaBuilder\CriteriaBuilderInterface;
use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;

abstract class AbstractPdoCollectorQuery
{

    const COLLECTOR_TOUCH_ID = AbstractPdoCollectorPlugin::COLLECTOR_TOUCH_ID;
    const COLLECTOR_RESOURCE_ID = AbstractPdoCollectorPlugin::COLLECTOR_RESOURCE_ID;

    /**
     * @var TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var CriteriaBuilderInterface
     */
    protected $criteriaBuilder;

    /**
     * @var LocaleTransfer
     */
    protected $locale;

    /**
     * @return void
     */
    abstract public function prepareQuery();

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

    /**
     * @return LocaleTransfer
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param LocaleTransfer $locale
     *
     * @return self
     */
    public function setLocale(LocaleTransfer $locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return TouchQueryContainerInterface
     */
    public function getTouchQueryContainer()
    {
        return $this->touchQueryContainer;
    }

    /**
     * @param TouchQueryContainerInterface $touchQueryContainer
     *
     * @return self
     */
    public function setTouchQueryContainer(TouchQueryContainerInterface $touchQueryContainer)
    {
        $this->touchQueryContainer = $touchQueryContainer;

        return $this;
    }

}
