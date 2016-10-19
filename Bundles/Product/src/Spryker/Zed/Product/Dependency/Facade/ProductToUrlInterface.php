<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency\Facade;

use Generated\Shared\Transfer\UrlTransfer;

interface ProductToUrlInterface
{

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveUrlAndTouch(UrlTransfer $urlTransfer);

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function deleteUrlAndTouch(UrlTransfer $urlTransfer);

    /**
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlActive($idUrl);

    /**
     * @param string $url
     *
     * @return bool
     */
    public function hasUrl($url);

    /**
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlDeleted($idUrl);

    /**
     * @param string $value
     *
     * @return string
     */
    public function slugify($value);

}
