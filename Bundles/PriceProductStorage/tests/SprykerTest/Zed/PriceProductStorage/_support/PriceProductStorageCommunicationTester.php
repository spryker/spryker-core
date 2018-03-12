<?php
namespace SprykerTest\Zed\PriceProductStorage;

use Codeception\Actor;

/**
 * Inherited Methods
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
class PriceProductStorageCommunicationTester extends Actor
{
    use _generated\PriceProductStorageCommunicationTesterActions;

   /**
    * Define custom actions here
    */

    const PROJECT = 'PROJECT';

    const PROJECT_SUITE = 'suite';

    /**
     * @return bool
     */
    public function isSuiteProject()
    {
        if (getenv(static::PROJECT) === static::PROJECT_SUITE) {
            return true;
        }

        return false;
    }
}
