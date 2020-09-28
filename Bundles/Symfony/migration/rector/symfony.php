<?php

/**
 * Copyright © 2020-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::SETS, [
        SetList::SYMFONY_34,
        SetList::SYMFONY_40,
        SetList::SYMFONY_41,
        SetList::SYMFONY_42,
        SetList::SYMFONY_43,
        SetList::SYMFONY_44,
        SetList::SYMFONY_50,
        SetList::SYMFONY_50_TYPES,
    ]);

    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
};
