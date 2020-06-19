<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui\Communication\Form\Constraint;

use Spryker\Zed\ContentNavigationGui\Dependency\Facade\ContentNavigationGuiToContentNavigationFacadeInterface;
use Spryker\Zed\ContentNavigationGui\Dependency\Service\ContentNavigationGuiToUtilEncodingServiceInterface;
use Symfony\Component\Validator\Constraint;

class ContentNavigationConstraint extends Constraint
{
    /**
     * @var \Spryker\Zed\ContentNavigationGui\Dependency\Facade\ContentNavigationGuiToContentNavigationFacadeInterface
     */
    protected $contentNavigationFacade;

    /**
     * @var \Spryker\Zed\ContentNavigationGui\Dependency\Service\ContentNavigationGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ContentNavigationGui\Dependency\Facade\ContentNavigationGuiToContentNavigationFacadeInterface $contentNavigationFacade
     * @param \Spryker\Zed\ContentNavigationGui\Dependency\Service\ContentNavigationGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param array|null $options
     */
    public function __construct(
        ContentNavigationGuiToContentNavigationFacadeInterface $contentNavigationFacade,
        ContentNavigationGuiToUtilEncodingServiceInterface $utilEncodingService,
        ?array $options = null
    ) {
        $this->contentNavigationFacade = $contentNavigationFacade;
        $this->utilEncodingService = $utilEncodingService;

        parent::__construct($options);
    }

    /**
     * @return \Spryker\Zed\ContentNavigationGui\Dependency\Facade\ContentNavigationGuiToContentNavigationFacadeInterface
     */
    public function getContentNavigationFacade(): ContentNavigationGuiToContentNavigationFacadeInterface
    {
        return $this->contentNavigationFacade;
    }

    /**
     * @return \Spryker\Zed\ContentNavigationGui\Dependency\Service\ContentNavigationGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ContentNavigationGuiToUtilEncodingServiceInterface
    {
        return $this->utilEncodingService;
    }
}
