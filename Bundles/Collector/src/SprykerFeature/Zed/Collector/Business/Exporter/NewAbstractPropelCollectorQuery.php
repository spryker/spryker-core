<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Everon\Component\CriteriaBuilder\BuilderInterface;
use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;

abstract class NewAbstractPropelCollectorQuery
{

    const COLLECTOR_TOUCH_ID = NewAbstractPropelCollectorPlugin::COLLECTOR_TOUCH_ID;
    const COLLECTOR_RESOURCE_ID = NewAbstractPropelCollectorPlugin::COLLECTOR_RESOURCE_ID;

    /**
     * @var TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var BuilderInterface
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
     * @return BuilderInterface
     */
    public function getCriteriaBuilder()
    {
        return $this->criteriaBuilder;
    }

    /**
     * @param BuilderInterface $criteriaBuilder
     *
     * @return self
     */
    public function setCriteriaBuilder(BuilderInterface $criteriaBuilder)
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
