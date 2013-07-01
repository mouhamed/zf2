<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Stdlib\Hydrator;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

trait StrategyEnabledTrait
{
    /**
     * The list with strategies that this hydrator has.
     *
     * @var ArrayObject
     */
    private $strategies = array();

    /**
     * Adds the given strategy under the given name.
     *
     * @param string $name The name of the strategy to register.
     * @param StrategyInterface $strategy The strategy to register.
     * @return StrategyEnabledInterface
     */
    public function addStrategy($name, StrategyInterface $strategy)
    {
        $this->strategies[$name] = $strategy;

        return $this;
    }

    /**
     * Gets the strategy with the given name.
     *
     * @param string $name The name of the strategy to get.
     * @return StrategyInterface
     */
    public function getStrategy($name)
    {
        if (isset($this->strategies[$name])) {
            return $this->strategies[$name];
        }

        if (!isset($this->strategies['*'])) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s: no strategy by name of "%s", and no wildcard strategy present',
                __METHOD__,
                $name
            ));
        }

        return $this->strategies['*'];
    }

    /**
     * Checks if the strategy with the given name exists.
     *
     * @param string $name The name of the strategy to check for.
     * @return bool
     */
    public function hasStrategy($name)
    {
        return array_key_exists($name, $this->strategies) ||
               array_key_exists('*', $this->strategies);
    }

    /**
     * Removes the strategy with the given name.
     *
     * @param string $name The name of the strategy to remove.
     * @return StrategyEnabledInterface
     */
    public function removeStrategy($name)
    {
        unset($this->strategies[$name]);

        return $this;
    }
    /**
     * Converts a value for extraction. If no strategy exists the plain value is returned.
     *
     * @param  string $name  The name of the strategy to use.
     * @param  mixed  $value  The value that should be converted.
     * @param  array  $object The object is optionally provided as context.
     * @return mixed
     */
    protected function extractValue($name, $value, $object = null)
    {
        $strategy = $this->hasStrategy($name) ? $this->getStrategy($name) : new DefaultStrategy();

        return $strategy->extract($value, $object);
    }

    /**
     * Converts a value for hydration. If no strategy exists the plain value is returned.
     *
     * @param string $name The name of the strategy to use.
     * @param mixed $value The value that should be converted.
     * @param array $data The whole data is optionally provided as context.
     * @return mixed
     */
    protected function hydrateValue($name, $value, $data = null)
    {
        $strategy = $this->hasStrategy($name) ? $this->getStrategy($name) : new DefaultStrategy();

        return $strategy->hydrate($value, $data);
    }
}
