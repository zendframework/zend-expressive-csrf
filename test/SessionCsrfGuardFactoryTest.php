<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-csrf for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-csrf/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Expressive\Csrf;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Csrf\Exception;
use Zend\Expressive\Csrf\SessionCsrfGuard;
use Zend\Expressive\Csrf\SessionCsrfGuardFactory;
use Zend\Expressive\Session\SessionInterface;
use Zend\Expressive\Session\SessionMiddleware;

class SessionCsrfGuardFactoryTest extends TestCase
{
    public function testConstructionUsesSaneDefaults()
    {
        $factory = new SessionCsrfGuardFactory();
        $this->assertAttributeSame(SessionMiddleware::SESSION_ATTRIBUTE, 'attributeKey', $factory);
    }

    public function testConstructionAllowsPassingAttributeKey()
    {
        $factory = new SessionCsrfGuardFactory('alternate-attribute');
        $this->assertAttributeSame('alternate-attribute', 'attributeKey', $factory);
    }

    public function attributeKeyProvider() : array
    {
        return [
            'default' => [SessionMiddleware::SESSION_ATTRIBUTE],
            'custom'  => ['custom-session-attribute'],
        ];
    }

    /**
     * @dataProvider attributeKeyProvider
     */
    public function testCreateGuardFromRequestRaisesExceptionIfAttributeDoesNotContainSession(string $attribute)
    {
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getAttribute($attribute, false)->willReturn(false);

        $factory = new SessionCsrfGuardFactory($attribute);

        $this->expectException(Exception\MissingSessionException::class);
        $factory->createGuardFromRequest($request->reveal());
    }

    /**
     * @dataProvider attributeKeyProvider
     */
    public function testCreateGuardFromRequestReturnsCsrfGuardWithSessionWhenPresent(string $attribute)
    {
        $session = $this->prophesize(SessionInterface::class)->reveal();
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getAttribute($attribute, false)->willReturn($session);

        $factory = new SessionCsrfGuardFactory($attribute);

        $guard = $factory->createGuardFromRequest($request->reveal());
        $this->assertInstanceOf(SessionCsrfGuard::class, $guard);
    }
}
