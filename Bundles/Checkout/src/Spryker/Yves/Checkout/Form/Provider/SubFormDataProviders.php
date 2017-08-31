<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Form\Provider;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;

class SubFormDataProviders implements StepEngineFormDataProviderInterface
{

    /**
     * @var \Spryker\Yves\Checkout\Form\Provider\FilterableSubFormProvider
     */
    protected $subFormProvider;

    /**
     * @param \Spryker\Yves\Checkout\Form\Provider\FilterableSubFormProvider $subFormProvider
     */
    public function __construct(FilterableSubFormProvider $subFormProvider)
    {
        $this->subFormProvider = $subFormProvider;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        return $this->getDataFromPlugins($this->subFormProvider->getSubForms($quoteTransfer), $quoteTransfer);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $formPluginCollection
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function getDataFromPlugins(SubFormPluginCollection $formPluginCollection, AbstractTransfer $quoteTransfer)
    {
        foreach ($formPluginCollection as $subForm) {
            $quoteTransfer = $subForm->createSubFormDataProvider()->getData($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        return $this->getOptionsFromPlugins($this->subFormProvider->getSubForms($quoteTransfer), $quoteTransfer);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $formPluginCollection
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getOptionsFromPlugins(SubFormPluginCollection $formPluginCollection, AbstractTransfer $quoteTransfer)
    {
        $options = [];
        foreach ($formPluginCollection as $subForm) {
            $options = array_merge(
                $options,
                $subForm->createSubFormDataProvider()->getOptions($quoteTransfer)
            );
        }

        return [
            SubFormInterface::OPTIONS_FIELD_NAME => $options,
        ];
    }

}
