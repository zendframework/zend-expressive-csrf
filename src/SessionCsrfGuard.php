<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-csrf for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-csrf/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Expressive\Csrf;

use Zend\Expressive\Session\SessionInterface;

class SessionCsrfGuard implements CsrfGuardInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function generateToken(string $keyName = '__csrf') : string
    {
        $token = bin2hex(random_bytes(16));
        $this->session->set($keyName, $token);
        return $token;
    }

    public function validateToken(string $token, string $csrfKey = '__csrf') : bool
    {
        $storedToken = $this->session->get($csrfKey, '');
        $this->session->unset($csrfKey);
        return $token === $storedToken;
    }
}
