<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint;

use Generated\Shared\Transfer\UrlTransfer;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueUrl extends SymfonyConstraint
{
    public const OPTION_URL_FACADE = 'urlFacade';

    /**
     * @var \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToUrlFacadeInterface
     */
    protected $urlFacade;

    /**
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function findExistingUrl(string $url): ?UrlTransfer
    {
        $urlTransfer = $this->createUrlTransfer($url);

        return $this->urlFacade->findUrlCaseInsensitive($urlTransfer);
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function hasUrlCaseInsensitive(string $url): bool
    {
        $urlTransfer = $this->createUrlTransfer($url);

        return $this->urlFacade->hasUrlCaseInsensitive($urlTransfer);
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }

    /**
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function createUrlTransfer(string $url): UrlTransfer
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl($url);

        return $urlTransfer;
    }
}
