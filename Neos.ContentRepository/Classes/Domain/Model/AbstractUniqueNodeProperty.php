<?php
namespace Neos\ContentRepository\Domain\Model;

/*
 * This file is part of the Neos.ContentRepository package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\ContentRepository\Domain\Service\Cache\UniqueNodePropertyCache;
use Neos\ContentRepository\Domain\Utility\BloomFilter;
use Neos\Flow\Annotations as Flow;
use Neos\Utility\TypeHandling;

/**
 * AbstractUniqueNodeProperty
 */
abstract class AbstractUniqueNodeProperty
{
    /**
     * @var UniqueNodePropertyCache
     * @Flow\Inject
     */
    protected $cache;

    /**
     * @var NodeInterface
     */
    protected $parentNode;

    /**
     * @var BloomFilter
     */
    protected $filter;

    /**
     * @var int
     */
    protected $maximumSize;

    /**
     * @var float
     */
    protected $probability;

    /**
     * @param NodeInterface $parentNode
     * @param int $maximumSize
     * @param float $probability
     */
    public function __construct(NodeInterface $parentNode, int $maximumSize = 40960, float $probability = 0.01)
    {
        $this->parentNode = $parentNode;
        $this->maximumSize = $maximumSize;
        $this->probability = $probability;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $this->load();
        return $this->filter->isEmpty() ? '' : $this->shortClassName() . ',' . $this->filter->serialize();
    }

    public function contains($element): bool
    {
        return $this->filter->contains($element);
    }

    /**
     * @return string
     */
    protected function shortClassName(): string
    {
        $classNameParts = explode('\\', TypeHandling::getTypeForValue($this));
        return array_pop($classNameParts);
    }

    /**
     * @return void
     */
    protected function load()
    {
        if ($this->filter instanceof BloomFilter) {
            return;
        }

        $value = trim($this->cache->get($this->cacheKey()));
        if ($value !== '') {
            $this->filter = BloomFilter::createFromSerilizedValue($value);
        } else {
            $this->filter = $this->build();
        }
    }

    /**
     * @return BloomFilter
     */
    protected function build(): BloomFilter
    {
        $filter = BloomFilter::create($this->maximumSize, $this->probability);
        $children = $this->parentNode->getChildNodes('Neos.Neos:Document');
        /** @var NodeInterface $node */
        foreach ($children as $node) {
            $value = $this->nodeUniqueProperty($node);
            if ($value === null) {
                continue;
            }
            $filter->add($value);
        }
        if (!$filter->isEmpty()) {
            $this->cache->set($this->cacheKey(), $filter->serialize());
        }

        return $filter;
    }

    /**
     * @param NodeInterface $node
     * @return mixed
     */
    protected function nodeUniqueProperty(NodeInterface $node)
    {
        return null;
    }

    /**
     * @return string
     */
    protected function cacheKey(): string
    {
        return md5(__CLASS__ . $this->maximumSize . $this->probability . $this->parentNode->getContextPath());
    }


}
