<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;

interface CategoryToUrlInterface
{

    /**
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $resourceType
     * @param int $resourceId
     *
     * @throws \Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $resourceId);

    /**
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlActive($idUrl);

    /**
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlDeleted($idUrl);

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveUrlAndTouch(UrlTransfer $urlTransfer);

    /**
     * @param string $url
     *
     * @return bool
     */
    public function hasUrl($url);

    /**
     * @param string $urlString
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingUrlException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function getUrlByPath($urlString);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function getResourceUrlByCategoryNodeIdAndLocale($idCategoryNode, LocaleTransfer $locale);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasResourceUrlByCategoryNodeIdAndLocale($idCategoryNode, LocaleTransfer $locale);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategoryNode
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    public function getResourceUrlCollectionByCategoryNodeId($idCategoryNode);

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingUrlException
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function deleteUrl(UrlTransfer $urlTransfer);

}
