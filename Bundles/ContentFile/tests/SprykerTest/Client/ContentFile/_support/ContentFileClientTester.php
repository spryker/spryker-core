<?php
namespace SprykerTest\Client\ContentFile;

use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\LocalizedContentTransfer;
use Spryker\Client\ContentFile\ContentFileClientInterface;

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
class ContentFileClientTester extends \Codeception\Actor
{
    use _generated\ContentFileClientTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function createBannerContentItem(): ContentTransfer
    {
        $data = [
            ContentTransfer::CONTENT_TERM_KEY => 'Banner',
            ContentTransfer::CONTENT_TYPE_KEY => 'Banner',
            ContentTransfer::DESCRIPTION => 'Test Banner',
            ContentTransfer::NAME => 'Test Banner',
            ContentTransfer::KEY => 'br-test',
            ContentTransfer::LOCALIZED_CONTENTS => [
                [
                    LocalizedContentTransfer::PARAMETERS => '{}',
                ],
            ],
        ];
        return $this->haveContent($data);
    }

    /**
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function createFileContentItem(): ContentTransfer
    {
        $data = [
            ContentTransfer::CONTENT_TERM_KEY => 'File List',
            ContentTransfer::CONTENT_TYPE_KEY => 'File List',
            ContentTransfer::DESCRIPTION => 'Test File List',
            ContentTransfer::NAME => 'Test File List',
            ContentTransfer::KEY => 'fl-test',
            ContentTransfer::LOCALIZED_CONTENTS => [
                [
                    LocalizedContentTransfer::PARAMETERS => '{}',
                ],
            ],
        ];
        return $this->haveContent($data);
    }

    /**
     * @return \Spryker\Client\ContentFile\ContentFileClientInterface
     */
    public function getClient(): ContentFileClientInterface
    {
        return $this->getLocator()->contentFile()->client();
    }
}
