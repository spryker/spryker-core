<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Dependency\Facade;

use Spryker\Zed\Url\Business\UrlFacadeInterface;

class CmsGuiToUrlBridge implements CmsGuiToUrlInterface
{

    /**
     * @var UrlFacadeInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\Url\Business\UrlFacadeInterface $urlFacade
     */
    public function __construct($urlFacade)
    {
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function hasUrl($url)
    {
       return $this->urlFacade->hasUrl($url);
    }

}
