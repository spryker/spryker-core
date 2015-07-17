<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Validator;

interface MenuLevelValidatorInterface
{

    /**
     * @param int $currentLevel
     * @param string $pageTitle
     *
     * @throws \Exception
     */
    public function validate($currentLevel, $pageTitle);

}
