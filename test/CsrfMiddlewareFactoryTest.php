<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-csrf for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-csrf/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Expressive\Csrf;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Csrf\CsrfGuardFactoryInterface;
use Zend\Expressive\Csrf\CsrfMiddleware;
use Zend\Expressive\Csrf\CsrfMiddlewareFactory;

class CsrfMiddlewareFactoryTest extends TestCase
{
    public function testFactoryReturnsMiddlewareUsingDefaultAttributeAndConfiguredGuardFactory()
    {
        $guardFactory = $this->prophesize(CsrfGuardFactoryInterface::class)->reveal();
        $container = $this->prophesize(ContainerInterface::class);
        $container->get(CsrfGuardFactoryInterface::class)->willReturn($guardFactory);

        $factory = new CsrfMiddlewareFactory();

        $middleware = $factory($container->reveal());

        $this->assertInstanceOf(CsrfMiddleware::class, $middleware);
        $this->assertAttributeSame($guardFactory, 'guardFactory', $middleware);
        $this->assertAttributeSame($middleware::GUARD_ATTRIBUTE, 'attributeKey', $middleware);
    }
}
