<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ContentFile;

use Codeception\Actor;
use Spryker\Client\ContentFile\ContentFileClientInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ContentFileClientTester extends Actor
{
    use _generated\ContentFileClientTesterActions;

    /**
     * @return \Spryker\Client\ContentFile\ContentFileClientInterface
     */
    public function getClient(): ContentFileClientInterface
    {
        return $this->getLocator()->contentFile()->client();
    }
}
