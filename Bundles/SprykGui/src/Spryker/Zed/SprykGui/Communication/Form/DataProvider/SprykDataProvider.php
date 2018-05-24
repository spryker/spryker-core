<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form\DataProvider;

use Spryker\Spryk\SprykFacade;

class SprykDataProvider
{
    /**
     * @param null|string $selectedSpryk
     *
     * @return array
     */
    public function getOptions(?string $selectedSpryk = null): array
    {
        $sprykFacade = new SprykFacade();
        $sprykDefinitions = $sprykFacade->getSprykDefinitions();

        $options = [
            'sprykDefinitions' => $sprykDefinitions,
        ];

        if ($selectedSpryk) {
            $options['spryk'] = $selectedSpryk;
        }

        return $options;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [];
    }
}
