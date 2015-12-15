<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Messenger\Business\Model;

use Spryker\Zed\Glossary\Business\GlossaryFacade;

class BaseMessageTray
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
        $translation = $keyName;
        if ($this->glossaryFacade->hasKey($keyName)) {
            $translation = $this->glossaryFacade->translate($keyName, $data);
        }

        return $translation;
    }

}
