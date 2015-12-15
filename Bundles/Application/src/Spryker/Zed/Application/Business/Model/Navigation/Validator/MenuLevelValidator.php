<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
