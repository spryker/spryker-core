<?php

namespace Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade;


interface LocaleFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale();

}