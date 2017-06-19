<?php

namespace Spryker\Zed\CmsBlockProductConnector\Dependency\Facade;


interface LocaleFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale();

}