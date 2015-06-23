<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\Decorator;

/**
 * Class Csv
 * @package SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\Decorator
 */
class CsvDecorator extends AbstractDecorator
{
    const SEPARATOR = 'separator';
    const ADD_TRAILING_SEPARATOR = 'addTrailingSeparator';

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @param array $values
     * @return mixed|string
     * @throws DecoratorException
     */
    public function decorate(array $values)
    {
        if (!isset($this->options[self::SEPARATOR])) {
            throw new DecoratorException('You must specify a field separator!');
        }
        $result = implode($this->options[self::SEPARATOR], $values);
        if (!isset($this->options[self::ADD_TRAILING_SEPARATOR])) {
            $result = rtrim($result, $this->options[self::SEPARATOR]);
        } else {
            if (substr($result, -1) !== $this->options[self::SEPARATOR]) {
                $result .= $this->options[self::SEPARATOR];
            }
        }
        return $result;
    }
}
