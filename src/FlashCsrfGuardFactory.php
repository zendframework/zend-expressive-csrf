<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-csrf for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-csrf/blob/master/LICENSE.md New BSD License
 */

namespace Zend\Expressive\Csrf;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Flash\FlashMessageMiddleware;
use Zend\Expressive\Flash\FlashMessagesInterface;

class FlashCsrfGuardFactory implements CsrfGuardFactoryInterface
{
    /**
     * @var string
     */
    private $attributeKey;

    public function __construct(string $attributeKey = FlashMessageMiddleware::FLASH_ATTRIBUTE)
    {
        $this->attributeKey = $attributeKey;
    }

    public function createGuardFromRequest(ServerRequestInterface $request) : CsrfGuardInterface
    {
        $flashMessages = $request->getAttribute($this->attributeKey, false);
        if (! $flashMessages instanceof FlashMessagesInterface) {
            throw Exception\MissingFlashMessagesException::create();
        }

        return new FlashCsrfGuard($flashMessages);
    }
}
