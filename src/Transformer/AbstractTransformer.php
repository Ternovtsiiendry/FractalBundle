<?php

/*
 * This file is part of the DmytrofFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\FractalBundle\Transformer;

use Doctrine\Common\Collections\{
    ArrayCollection, Collection
};
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;
use Dmytrof\FractalBundle\{
    Exception\TransformerException, Service\ExtensionsContainer, Transformer\Extension\ExtensionInterface
};

abstract class AbstractTransformer extends TransformerAbstract
{
    protected const SUBJECT_CLASS = null;

    /**
     * @var ExtensionsContainer
     */
    protected $extensionsContainer;

    /**
     * @var Collection|ExtensionInterface[]
     */
    protected $extensions;

    /**
     * @var array
     */
    protected $includeCalls;

    /**
     * @var array
     */
    protected $includesToRoot;

    /**
     * @var bool
     */
    protected $isSetup = false;

    /**
     * AbstractTransformer constructor.
     * @param ExtensionsContainer $extensionsContainer
     */
    public function __construct(ExtensionsContainer $extensionsContainer)
    {
        $this->extensionsContainer = $extensionsContainer;
    }

    /**
     * Initiates default data
     */
    protected function init(): void
    {
        foreach ($this->getExtensions() as $extension) {
            $extension->decorateTransformer($this);
        }
    }

    /**
     * Setup the transformer
     * @return AbstractTransformer
     */
    public function setup(): self
    {
        if (!$this->isSetup) {
            $this->isSetup = true;
            $this->init();
        }
        return $this;
    }

    /**
     * @return ExtensionsContainer
     */
    public function getExtensionsContainer(): ExtensionsContainer
    {
        return $this->extensionsContainer;
    }

    /**
     * Returns subject class
     * @return string
     */
    public function getSubjectClass(): string
    {
        return static::SUBJECT_CLASS;
    }

    /**
     * Checks if transformer supports subject
     * @param $subject
     * @return bool
     */
    public function supports($subject): bool
    {
        $className = $this->getSubjectClass();
        return is_null($subject) || $subject instanceof $className;
    }

    /**
     * Returns needed extensions
     * @return Collection|ExtensionInterface[]
     */
    public function getExtensions(): Collection
    {
        if (!isset($this->extensions)) {
            $this->extensions = new ArrayCollection();
            try {
                $reflectionClass = new \ReflectionClass($this->getSubjectClass());
                /** @var ExtensionInterface $extension */
                foreach ($this->getExtensionsContainer() as $extension) {
                    if ($extension->supports($reflectionClass, $this)) {
                        $this->extensions->set(get_class($extension), $extension);
                    }
                }
            } catch (\ReflectionException $e) {
            }
        }
        return $this->extensions;
    }

    /**
     * Adds default include
     * @param string $defaultInclude
     * @return AbstractTransformer
     */
    public function addDefaultInclude(string $defaultInclude): self
    {
        return $this->setDefaultIncludes(array_merge($this->getDefaultIncludes(), func_get_args()));
    }

    /**
     * Removes default include
     * @param string $defaultInclude
     * @return AbstractTransformer
     */
    public function removeDefaultInclude(string $defaultInclude): self
    {
        return $this->setDefaultIncludes(array_diff($this->getDefaultIncludes(), func_get_args()));
    }

    /**
     * Resets default includes
     * @return AbstractTransformer
     */
    public function resetDefaultIncludes(): self
    {
        return $this->setDefaultIncludes([]);
    }

    /**
     * Adds available include
     * @param string $include
     * @return AbstractTransformer
     */
    public function addAvailableInclude(string $include): self
    {
        return $this->setAvailableIncludes(array_merge($this->getAvailableIncludes(), func_get_args()));
    }

    /**
     * Removes available include
     * @param string $include
     * @return AbstractTransformer
     */
    public function removeAvailableInclude(string $include): self
    {
        return $this->setAvailableIncludes(array_diff($this->getAvailableIncludes(), func_get_args()));
    }

    /**
     * Returns include calls
     * @return array
     */
    public function getIncludeCalls(): array
    {
        return (array) $this->includeCalls;
    }

    /**
     * Sets include calls
     * @param array $includeCalls
     * @return AbstractTransformer
     */
    public function setIncludeCalls(array $includeCalls): self
    {
        $this->includeCalls = $includeCalls;
        return $this;
    }

    /**
     * Checks if include call exists
     * @param string $include
     * @return bool
     */
    public function hasIncludeCall(string $include): bool
    {
        return isset($this->getIncludeCalls()[$include]);
    }

    /**
     * Sets include call
     * @param string $include
     * @param callable $call
     * @return AbstractTransformer
     */
    public function setIncludeCall(string $include, callable $call): self
    {
        return $this->setIncludeCalls(array_merge($this->getIncludeCalls(), [$include => $call]));
    }

    /**
     * Returns include call
     * @param string $include
     * @return callable|null
     */
    public function getIncludeCall(string $include): ?callable
    {
        return $this->hasIncludeCall($include) ? $this->getIncludeCalls()[$include] : null;
    }

    /**
     * Removes include call
     * @param string $include
     * @return AbstractTransformer
     */
    public function removeIncludeCall(string $include): self
    {
        return $this->setIncludeCalls(array_diff_key($this->getIncludeCalls(), [$include => $include]));
    }

    /**
     * Returns includes to root
     * @return array
     */
    public function getIncludesToRoot(): array
    {
        return (array) $this->includesToRoot;
    }

    /**
     * Sets includes to root
     * @param array $includesToRoot
     * @return AbstractTransformer
     */
    public function setIncludesToRoot(array $includesToRoot): self
    {
        $this->includesToRoot = $includesToRoot;
        return $this;
    }

    /**
     * Checks if include to root exists
     * @param string $include
     * @return bool
     */
    public function hasIncludeToRoot(string $include): bool
    {
        return in_array($include, $this->getIncludesToRoot());
    }

    /**
     * Adds include to root
     * @param string $include
     * @return AbstractTransformer
     */
    public function addIncludeToRoot(string $include): self
    {
        return $this->setIncludesToRoot(array_merge($this->getIncludesToRoot(), func_get_args()));
    }

    /**
     * Removes include to root
     * @param string $include
     * @return AbstractTransformer
     */
    public function removeIncludeToRoot(string $include): self
    {
        return $this->setIncludesToRoot(array_diff($this->getIncludesToRoot(), func_get_args()));
    }

    /**
     * Transforms data
     * @param \DateTime|null $dateTime
     * @param string $format
     * @return string|null
     */
    public function transformDateTime(?\DateTime $dateTime, string $format = \DateTime::ATOM): ?string
    {
        return $dateTime ? $dateTime->format($format) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($subject)
    {
        $this->setup();
        if (!$this->supports($subject)) {
            throw new TransformerException(sprintf('%s transforms %s subjects only.', static::class, $this->getSubjectClass()));
        }
        if (!$subject) {
            return [];
        }

        $extensionsTransformData = [];
        foreach ($this->getExtensions() as $extension) {
            $extensionsTransformData += $extension->transform($subject, $this);
        }

        return $this->transformSubject($subject) + $extensionsTransformData;
    }

    /**
     * Call Include Method.
     *
     * @internal
     *
     * @param Scope  $scope
     * @param string $includeName
     * @param mixed  $data
     *
     * @throws \Exception
     *
     * @return \League\Fractal\Resource\ResourceInterface
     */
    protected function callIncludeMethod(Scope $scope, $includeName, $data)
    {
        if ($this->hasIncludeCall($includeName)) {
            $scopeIdentifier = $scope->getIdentifier($includeName);
            $params = $scope->getManager()->getIncludeParams($scopeIdentifier);

            return call_user_func($this->getIncludeCall($includeName), $data, $this, $params);
        }
        return parent::callIncludeMethod($scope, $includeName, $data);
    }

    abstract public function transformSubject($subject): array;
}