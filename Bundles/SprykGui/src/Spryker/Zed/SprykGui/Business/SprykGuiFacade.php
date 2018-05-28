<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiBusinessFactory getFactory()
 * @method \Spryker\Zed\SprykGui\Persistence\SprykGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\SprykGui\Persistence\SprykGuiEntityManagerInterface getEntityManager()
 */
class SprykGuiFacade extends AbstractFacade implements SprykGuiFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return array
     */
    public function buildSprykView(string $sprykName, array $sprykArguments): array
    {
        return $this->getFactory()->createSpryk()->buildSprykView($sprykName, $sprykArguments);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function getSprykDefinitions(): array
    {
        return $this->getFactory()->createSpryk()->getSprykDefinitions();
    }


    public function drawSpryk($sprykName): string
    {
        return $this->getFactory()->createSpryk()->drawSpryk($sprykName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return bool
     */
    public function runSpryk(string $sprykName, array $sprykArguments): bool
    {
        return $this->getFactory()->createSpryk()->runSpryk($sprykName, $sprykArguments);
    }
}
