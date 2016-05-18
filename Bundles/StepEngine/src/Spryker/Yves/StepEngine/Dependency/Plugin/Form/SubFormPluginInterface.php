<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Dependency\Plugin\Form;

interface SubFormPluginInterface
{

    /**
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createSubForm();

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\DataProviderInterface
     */
    public function createSubFormDataProvider();

}
