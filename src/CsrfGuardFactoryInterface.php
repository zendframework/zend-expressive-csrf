<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-csrf for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-csrf/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Expressive\Csrf;

use Psr\Http\Message\ServerRequestInterface;

interface CsrfGuardFactoryInterface
{
    public function createGuardFromRequest(ServerRequestInterface $request) : CsrfGuardInterface;
}
