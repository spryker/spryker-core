<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\DecisionRule;

use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;

class BaseDecisionRule
{

    /**
     * @var GlossaryFacade
     */
    protected $glossaryFacade;

    /**
     * @param GlossaryFacade $glossaryFacade
     */
    public function __construct(GlossaryFacade $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param string $keyName
     * @param array $data
     *
     * @return string
     */
    protected function translate($keyName, array $data = [])
    {
        if (!$this->glossaryFacade->hasKey($keyName)) {
            return $keyName;
        }

        return $this->glossaryFacade->translate($keyName, $data);
    }

}
