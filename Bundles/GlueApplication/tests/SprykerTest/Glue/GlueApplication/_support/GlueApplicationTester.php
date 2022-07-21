<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication;

use Codeception\Actor;
use Codeception\Configuration;
use Generated\Shared\Transfer\ApiControllerConfigurationTransfer;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Symfony\Component\Finder\Finder;

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
class GlueApplicationTester extends Actor
{
    use _generated\GlueApplicationTesterActions;

    /**
     * @var string
     */
    public const FAKE_APPLICATION = 'FAKE_APPLICATION';

    /**
     * @var string
     */
    public const FAKE_CONTROLLER = 'FAKE_CONTROLLER';

    /**
     * @var string
     */
    public const FAKE_METHOD = 'FAKE_METHOD';

    /**
     * @var string
     */
    public const FAKE_PATH = 'FAKE_PATH';

    /**
     * @var string
     */
    public const FAKE_PARAMETER_FOO = 'FAKE_PARAMETER_FOO';

    /**
     * @var string
     */
    public const FAKE_PARAMETER_BAR = 'FAKE_PARAMETER_BAR';

    /**
     * @return array<string, array<string, \Generated\Shared\Transfer\ApiControllerConfigurationTransfer>>
     */
    public function haveApiControllerConfigurationTransfers(): array
    {
        return [
            static::FAKE_APPLICATION => [
                sprintf('%s:%s:%s', static::FAKE_CONTROLLER, static::FAKE_PATH, static::FAKE_METHOD) =>
                    (new ApiControllerConfigurationTransfer())
                        ->setApiApplication(static::FAKE_APPLICATION)
                        ->setController(static::FAKE_CONTROLLER)
                        ->setMethod(static::FAKE_METHOD)
                        ->setPath(static::FAKE_PATH)
                        ->setParameters([static::FAKE_PARAMETER_FOO, static::FAKE_PARAMETER_BAR]),
            ],
        ];
    }

    /**
     * @return void
     */
    public function removeCacheFile(): void
    {
        if (file_exists(Configuration::dataDir() . DIRECTORY_SEPARATOR . GlueApplicationConfig::API_CONTROLLER_CACHE_FILENAME)) {
            $finder = new Finder();
            $finder->in(Configuration::dataDir())->name(GlueApplicationConfig::API_CONTROLLER_CACHE_FILENAME);
            if ($finder->count() > 0) {
                foreach ($finder as $fileInfo) {
                    unlink($fileInfo->getPathname());
                }
            }
        }
    }
}
