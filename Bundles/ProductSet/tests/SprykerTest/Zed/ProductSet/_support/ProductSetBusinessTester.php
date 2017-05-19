<?php
namespace SprykerTest\Zed\ProductSet;

use Codeception\Actor;
use Generated\Shared\DataBuilder\LocalizedProductSetBuilder;
use Generated\Shared\DataBuilder\ProductImageSetBuilder;
use Generated\Shared\DataBuilder\ProductSetBuilder;
use Generated\Shared\Transfer\LocalizedProductSetTransfer;

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
 * @method \Spryker\Zed\ProductSet\Business\ProductSetFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductSetBusinessTester extends Actor
{

    use _generated\ProductSetBusinessTesterActions;

    /**
     * @param array $productSetSeed
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function generateProductSetTransfer(array $productSetSeed = [])
    {
        $localeTransfer = $this->haveLocale();

        $productSetTransfer = (new ProductSetBuilder())
            ->seed($productSetSeed)
            ->withLocalizedData(
                (new LocalizedProductSetBuilder())
                    ->seed([
                        LocalizedProductSetTransfer::LOCALE => $localeTransfer,
                    ])
                    ->withProductSetData()
            )
            ->withImageSet(
                (new ProductImageSetBuilder())
                    ->withProductImage()
            )
            ->build();

        return $productSetTransfer;
    }

}
