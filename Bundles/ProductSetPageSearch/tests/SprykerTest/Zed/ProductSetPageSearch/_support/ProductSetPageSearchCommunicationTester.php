<?php
namespace SprykerTest\Zed\ProductSetPageSearch;

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
class ProductSetPageSearchCommunicationTester extends Actor
{
    use _generated\ProductSetPageSearchCommunicationTesterActions;

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
        if (isset($_SERVER[static::PROJECT]) && $_SERVER[static::PROJECT] === static::PROJECT_SUITE) {
            return true;
        }

        return false;
    }
}
