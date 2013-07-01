<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Stdlib\Hydrator;

use Zend\Stdlib\Hydrator\Filter\FilterComposite;

trait FilterEnabledTrait
{
    /**
     * Composite to filter the methods, that need to be hydrated
     * @var Filter\FilterComposite
     */
    private $filterComposite;

    /**
     * Add a new filter to take care of what needs to be hydrated.
     * To exclude e.g. the method getServiceLocator:
     *
     * <code>
     * $composite->addFilter("servicelocator",
     *     function($property) {
     *         list($class, $method) = explode('::', $property);
     *         if ($method === 'getServiceLocator') {
     *             return false;
     *         }
     *         return true;
     *     }, FilterComposite::CONDITION_AND
     * );
     * </code>
     *
     * @param string $name Index in the composite
     * @param callable|FilterInterface $filter
     * @param int $condition
     * @return FilterComposite
     */
    public function addFilter($name, $filter, $condition = FilterComposite::CONDITION_OR)
    {
        return $this->getFilter()->addFilter($name, $filter, $condition);
    }

    /**
     * Get the filter instance
     *
     * @return FilterComposite
     */
    public function getFilter()
    {
        if ($this->filterComposite === null) {
            $this->filterComposite = new FilterComposite();
        }

        return $this->filterComposite;
    }

    /**
     * Check whether a specific filter exists at key $name or not
     *
     * @param string $name Index in the composite
     * @return bool
     */
    public function hasFilter($name)
    {
        return $this->getFilter()->hasFilter($name);
    }

    /**
     * Remove a filter from the composition.
     * To not extract "has" methods, you simply need to unregister it
     *
     * <code>
     * $filterComposite->removeFilter('has');
     * </code>
     *
     * @param $name
     * @return FilterComposite
     */
    public function removeFilter($name)
    {
        return $this->getFilter()->removeFilter($name);
    }
}
