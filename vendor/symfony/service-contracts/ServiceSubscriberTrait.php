<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Convoworks\Symfony\Contracts\Service;

use Psr\Container\ContainerInterface;
/**
 * Implementation of ServiceSubscriberInterface that determines subscribed services from
 * private method return types. Service ids are available as "ClassName::methodName".
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
trait ServiceSubscriberTrait
{
    /** @var ContainerInterface */
    protected $container;
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices() : array
    {
        static $services;
        if (null !== $services) {
            return $services;
        }
        $services = \is_callable(['parent', __FUNCTION__]) ? parent::getSubscribedServices() : [];
        foreach ((new \ReflectionClass(self::class))->getMethods() as $method) {
            if ($method->isStatic() || $method->isAbstract() || $method->isGenerator() || $method->isInternal() || $method->getNumberOfRequiredParameters()) {
                continue;
            }
            if (self::class !== $method->getDeclaringClass()->name) {
                continue;
            }
            if (!($returnType = $method->getReturnType()) instanceof \ReflectionNamedType) {
                continue;
            }
            if ($returnType->isBuiltin()) {
                continue;
            }
            $services[self::class . '::' . $method->name] = '?' . $returnType->getName();
        }
        return $services;
    }
    /**
     * @required
     *
     * @return ContainerInterface|null
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        if (\is_callable(['parent', __FUNCTION__])) {
            return parent::setContainer($container);
        }
        return null;
    }
}
