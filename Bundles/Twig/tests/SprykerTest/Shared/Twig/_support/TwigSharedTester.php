<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Twig;

use Codeception\Actor;
use Twig\Environment;
use Twig\Loader\LoaderInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class TwigSharedTester extends Actor
{
    use _generated\TwigSharedTesterActions;

    /**
     * @var array
     */
    public const ENVIRONMENT_FILTERS = ['sort', 'filter', 'map', 'reduce', 'find'];

    /**
     * @param \Twig\Loader\LoaderInterface|null $loader
     *
     * @return \Twig\Environment
     */
    public function createTwigEnvironment(?LoaderInterface $loader = null): Environment
    {
        return new Environment($loader);
    }
}
