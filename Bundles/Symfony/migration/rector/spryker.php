<?php

/**
 * Copyright © 2020-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

use Rector\Core\Configuration\Option;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Spryker\Rector\Symfony\Security\New_\BCryptToNativePasswordEncoderArgumentRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Rector\SymfonyPhpConfig\inline_value_objects;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(RenameClassRector::class)
        ->call('configure', [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                'Symfony\Component\Translation\TranslatorInterface' => 'Symfony\Contracts\Translation\TranslatorInterface',
                'Symfony\Component\Debug\Exception\FlattenException' => 'Symfony\Component\ErrorHandler\Exception\FlattenException',
                'Symfony\Component\Validator\ValidatorBuilderInterface' => 'Symfony\Component\Validator\ValidatorBuilder',
            ],
        ]]);

    $services->set(RenameMethodRector::class)
        ->call('configure', [[
            RenameMethodRector::METHOD_CALL_RENAMES => inline_value_objects([
                new MethodCallRename('Symfony\Component\HttpKernel\Event\ExceptionEvent', 'getException', 'getThrowable'),
            ]),
        ]]);

    $services->set(BCryptToNativePasswordEncoderArgumentRector::class);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
};
