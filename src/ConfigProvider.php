<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-csrf for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-csrf/blob/master/LICENSE.md New BSD License
 */

namespace Zend\Expressive\Csrf;

class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies() : array
    {
        return [
            'aliases' => [
                // Change this to the CsrfGuardFactoryInterface implementation you wish to use:
                CsrfGuardFactoryInterface::class => FlashCsrfGuardFactory::class,
            ],
            'invokables' => [
                FlashCsrfGuardFactory::class   => FlashCsrfGuardFactory::class,
                SessionCsrfGuardFactory::class => SessionCsrfGuardFactory::class,
            ],
            'factories' => [
                CsrfMiddleware::class => CsrfMiddlewareFactory::class,
            ],
        ];
    }
}
