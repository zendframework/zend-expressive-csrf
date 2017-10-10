<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-csrf for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-csrf/blob/master/LICENSE.md New BSD License
 */

namespace Zend\Expressive\Csrf;

use Psr\Container\ContainerInterface;

class CsrfMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new CsrfMiddleware(
            $container->get(CsrfGuardFactoryInterface::class)
        );
    }
}
