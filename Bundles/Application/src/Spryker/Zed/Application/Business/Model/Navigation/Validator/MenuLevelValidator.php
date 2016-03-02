<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Business\Model\Navigation\Validator;

class MenuLevelValidator implements MenuLevelValidatorInterface
{

    /**
     * @var int
     */
    protected $maxLevelCount;

    /**
     * @param int $maxLevelCount
     */
    public function __construct($maxLevelCount)
    {
        $this->maxLevelCount = $maxLevelCount;
    }

    /**
     * @param int $currentLevel
     * @param string $pageTitle
     *
     * @throws \Exception
     *
     * @return void
     */
    public function validate($currentLevel, $pageTitle)
    {
        if ($this->maxLevelCount < $currentLevel) {
            throw new MenuLevelException($this->maxLevelCount, $pageTitle);
        }
    }

}
