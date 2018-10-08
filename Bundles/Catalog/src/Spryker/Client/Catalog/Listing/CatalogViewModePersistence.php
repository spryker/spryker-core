<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Listing;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CatalogViewModePersistence implements CatalogViewModePersistenceInterface
{
    public const COOKIE_IDENTIFIER = 'catalog-view-mode';

    public const VIEW_MODE_GRID = 'grid';
    public const VIEW_MODE_LIST = 'list';

    /**
     * @var string
     */
    protected $defaultViewMode;

    /**
     * @param string $defaultListMode
     */
    public function __construct($defaultListMode = self::VIEW_MODE_GRID)
    {
        $this->defaultViewMode = $defaultListMode;
    }

    /**
     * @param string $mode
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function setViewMode($mode, Response $response)
    {
        $response->headers->setCookie(new Cookie(static::COOKIE_IDENTIFIER, $mode));

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function getViewMode(Request $request)
    {
        $listingMode = $request->cookies->get(static::COOKIE_IDENTIFIER);

        if (!$listingMode) {
            return $this->defaultViewMode;
        }

        return $listingMode;
    }
}
