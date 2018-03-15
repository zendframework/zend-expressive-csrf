<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-csrf for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-csrf/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Expressive\Csrf\Exception;

use RuntimeException;
use Zend\Expressive\Csrf\SessionCsrfGuard;
use Zend\Expressive\Session\SessionMiddleware;

use function sprintf;

class MissingSessionException extends RuntimeException implements ExceptionInterface
{
    public static function create() : self
    {
        return new self(sprintf(
            'Cannot create %s; could not locate session in request. '
            . 'Make sure the %s is piped to your application.',
            SessionCsrfGuard::class,
            SessionMiddleware::class
        ));
    }
}
