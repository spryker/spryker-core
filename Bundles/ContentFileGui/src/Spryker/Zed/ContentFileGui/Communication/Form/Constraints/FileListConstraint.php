<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Communication\Form\Constraints;

use Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToContentFileInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class FileListConstraint extends SymfonyConstraint
{
    /**
     * @var \Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToContentFileInterface
     */
    protected $contentFileFacade;

    /**
     * @param \Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToContentFileInterface $contentFileFacade
     * @param array|null $options
     */
    public function __construct(ContentFileGuiToContentFileInterface $contentFileFacade, ?array $options = null)
    {
        $this->contentFileFacade = $contentFileFacade;

        parent::__construct($options);
    }

    /**
     * @return \Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToContentFileInterface
     */
    public function getContentFileFacade(): ContentFileGuiToContentFileInterface
    {
        return $this->contentFileFacade;
    }
}
