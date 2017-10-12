<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data\Url;

use Generated\Shared\Transfer\LocalizedProductSetTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToUrlInterface;

class ProductSetUrlCreator implements ProductSetUrlCreatorInterface
{
    /**
     * @var \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToUrlInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToUrlInterface $urlFacade
     */
    public function __construct(ProductSetToUrlInterface $urlFacade)
    {
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedProductSetTransfer $localizedProductSetTransfer
     * @param int $idProductSet
     *
     * @return \Generated\Shared\Transfer\LocalizedProductSetTransfer
     */
    public function createUrl(LocalizedProductSetTransfer $localizedProductSetTransfer, $idProductSet)
    {
        $this->assertProductSetForCreateUrl($localizedProductSetTransfer);

        $urlTransfer = new UrlTransfer();
        $urlTransfer
            ->setUrl($localizedProductSetTransfer->getUrl())
            ->setFkResourceProductSet($idProductSet)
            ->setFkLocale($localizedProductSetTransfer->getLocale()->getIdLocale());

        $this->urlFacade->createUrl($urlTransfer);

        return $localizedProductSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedProductSetTransfer $localizedProductSetTransfer
     *
     * @return void
     */
    protected function assertProductSetForCreateUrl(LocalizedProductSetTransfer $localizedProductSetTransfer)
    {
        $localizedProductSetTransfer
            ->requireUrl()
            ->requireLocale();

        $localizedProductSetTransfer->getLocale()->requireIdLocale();
    }
}
