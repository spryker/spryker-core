<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion;

use SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\PropelMethodTagBuilder;
use SprykerFeature\Shared\Library\Autoloader;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Kernel
 * @group PropelMethodTagBuilder
 */
class PropelMethodTagBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testBuildMethodTagsShouldReturnOnlyNotIgnoredClasses()
    {
        Autoloader::allowNamespace('VendorNamespace');
        $options = [
            PropelMethodTagBuilder::OPTION_KEY_APPLICATION => 'Application',
            PropelMethodTagBuilder::OPTION_KEY_PATH_PATTERN => 'Persistence/',
            PropelMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src/',
            PropelMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src/',
        ];

        require_once __DIR__ . '/Fixtures/vendor/vendor/package/src/VendorNamespace/Application/Bundle/Persistence/Propel/Bar.php';
        require_once __DIR__ . '/Fixtures/vendor/vendor/package/src/VendorNamespace/Application/Bundle/Persistence/Propel/Base/IgnoredClass.php';
        require_once __DIR__ . '/Fixtures/vendor/vendor/package/src/VendorNamespace/Application/Bundle/Persistence/Propel/Map/IgnoredClass.php';

        $methodTagBuilder = new PropelMethodTagBuilder($options);
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');

        $expectedMethodTag =
            ' * @method \VendorNamespace\Application\Bundle\Persistence\Propel\Bar createPropelBar()'
        ;
        $this->assertContains($expectedMethodTag, $methodTags);

        $notExpectedMethodTag =
            ' * @method \VendorNamespace\Application\Bundle\Persistence\Propel\Base\IgnoredClass createPropelBaseIgnoredClass()'
        ;
        $this->assertNotContains($notExpectedMethodTag, $methodTags);

        $notExpectedMethodTag =
            ' * @method \VendorNamespace\Application\Bundle\Persistence\Propel\Map\IgnoredClass createPropelMapIgnoredClass()'
        ;
        $this->assertNotContains($notExpectedMethodTag, $methodTags);
    }

}
