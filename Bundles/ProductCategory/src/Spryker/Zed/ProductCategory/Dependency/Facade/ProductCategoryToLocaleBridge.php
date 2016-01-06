<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

class ProductCategoryToLocaleBridge implements ProductCategoryToLocaleInterface
{

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * ProductCategoryToLocaleBridge constructor.
     *
     * @param \Spryker\Zed\Locale\Business\LocaleFacade $localeFacade
     */
    public function __construct($localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale()
    {
        return $this->localeFacade->getCurrentLocale();
    }

}
