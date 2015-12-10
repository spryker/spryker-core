<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Persistence\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

abstract class AbstractCollectorQuery
{

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $locale;

    /**
     * @return void
     */
    abstract protected function prepareQuery();

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return self
     */
    public function setLocale(LocaleTransfer $locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    public function getTouchQueryContainer()
    {
        return $this->touchQueryContainer;
    }

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $touchQueryContainer
     *
     * @return self
     */
    public function setTouchQueryContainer(TouchQueryContainerInterface $touchQueryContainer)
    {
        $this->touchQueryContainer = $touchQueryContainer;

        return $this;
    }

    /**
     * @return self
     */
    public function prepare()
    {
        $this->prepareQuery();

        return $this;
    }

}
