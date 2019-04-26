<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication\Form\Constraints;

use Spryker\Zed\ContentBannerGui\Dependency\Facade\ContentBannerGuiToContentBannerInterface;
use Spryker\Zed\ContentBannerGui\Dependency\Service\ContentBannerGuiToUtilEncodingInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ContentBannerConstraint extends SymfonyConstraint
{
    /**
     * @var \Spryker\Zed\ContentBannerGui\Dependency\Facade\ContentBannerGuiToContentBannerInterface
     */
    protected $contentBannerFacade;

    /**
     * @var \Spryker\Zed\ContentBannerGui\Dependency\Service\ContentBannerGuiToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @param \Spryker\Zed\ContentBannerGui\Dependency\Facade\ContentBannerGuiToContentBannerInterface $contentBannerFacade
     * @param \Spryker\Zed\ContentBannerGui\Dependency\Service\ContentBannerGuiToUtilEncodingInterface $utilEncoding
     * @param array|null $options
     */
    public function __construct(
        ContentBannerGuiToContentBannerInterface $contentBannerFacade,
        ContentBannerGuiToUtilEncodingInterface $utilEncoding,
        ?array $options = null
    ) {
        $this->contentBannerFacade = $contentBannerFacade;
        $this->utilEncoding = $utilEncoding;

        parent::__construct($options);
    }

    /**
     * @return \Spryker\Zed\ContentBannerGui\Dependency\Facade\ContentBannerGuiToContentBannerInterface
     */
    public function getContentBannerFacade(): ContentBannerGuiToContentBannerInterface
    {
        return $this->contentBannerFacade;
    }

    /**
     * @return \Spryker\Zed\ContentBannerGui\Dependency\Service\ContentBannerGuiToUtilEncodingInterface
     */
    public function getUtilEncoding(): ContentBannerGuiToUtilEncodingInterface
    {
        return $this->utilEncoding;
    }
}
