# CSRF Guards

To provide CSRF protection, we provide an abstraction,
`Zend\Expressive\Csrf\CsrfGuardInterface`:

```php
namespace Zend\Expressive\Csrf;

interface CsrfGuardInterface
{
    /**
     * Generate a CSRF token.
     *
     * Typically, implementations should generate a one-time CSRF token,
     * store it within the session, and return it so that developers may
     * then inject it in a form, a response header, etc.
     *
     * CSRF tokens should EXPIRE after the first hop.
     */
    public function generateToken(string $keyName = '__csrf') : string;

    /**
     * Validate whether a submitted CSRF token is the same as the one stored in
     * the session.
     *
     * CSRF tokens should EXPIRE after the first hop.
     */
    public function validateToken(string $token, string $csrfKey = '__csrf') : bool;
}
```

Because guards will be backed by different mechanisms, we provide
[CsrfMiddleware](middleware.md) that will generate the guard based on
configuration, and inject it into the request passed to later middleware; this
approach allows you to separate generation from the guard instance (which is based
on request data) from your own middleware.

Once you have a concrete implementation, you will generally:

- _Generate_ a token in middleware displaying a form, and
- _Validate_ a token in middleware validating that form.

As an example, we could have middleware displaying a form as follows:

```php
namespace Books;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Csrf\CsrfGuardInterface;
use Zend\Expressive\Csrf\CsrfMiddleware;
use Zend\Expressive\Template\TemplateRendererInterface;

class DisplayBookFormHandler implements MiddlewareInterface
{
    private $renderer;

    public function __construct(TemplateRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $guard = $request->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
        $token = $guard->generateToken();

        return new HtmlResponse(
            $this->renderer->render('books::form', [
                '__csrf' => $token,
            ]);
        );
    }
}
```

When we're ready to process it, we then might have the following middleware:

```php
namespace Books;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Csrf\CsrfGuardInterface;
use Zend\Expressive\Csrf\CsrfMiddleware;
use Zend\Expressive\Template\TemplateRendererInterface;

class ProcessBookFormHandler implements MiddlewareInterface
{
    private $renderer;

    public function __construct(TemplateRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $guard = $request->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
        $data  = $request->getParsedBody();
        $token = $data['__csrf'] ?? '';

        if (! $guard->validateToken($token)) {
            return new EmptyResponse(412); // Precondition failed
        }

        // process form normally and return a response...
    }
}
```

This approach allows you to prevent CSRF attacks _separately_ from normal form
validation, which can also simplify how your forms are structured.

We provide two guard implementations, one using the base session container
functionality from zend-expressive-session, and another using flash messages as
provided in zend-expressive-flash.

## Session-based guard

Session-based guards are provided via `Zend\Expressive\Csrf\SessionCsrfGuard`.
This class expects a `Zend\Expressive\Session\SessionInterface` instance to its
constructor, and it then uses that to both store a token in the session during
`generateToken()`, and when validating a submitted token.

## Flash-based guard

Flash guards are provided via `Zend\Expressive\Csrf\FlashCsrfGuard`.  This class
expects a `Zend\Expressive\Flash\FlashMessagesInterface` instance to its
constructor, and it then uses that to store a token via a flash message when
`generateToken()` is called, and to retrieve a previously flashed token when
validating a submitted token.

To use this guard, you will also need to install the zend-expressive-flash
package:

```bash
$ composer require zendframework/zend-expressive-flash
```

## Guard factories

Because guard implementations generally require request-based artifacts in order
to do their work, we provide an interface describing a factory for generating
guards. Essentially, each guard implementation will also supply their own
factory implementation, which the [CsrfMiddleware](middleware.md) will then
consume to create a guard instance.

`Zend\Expressive\Csrf\CsrfGuardFactoryInterface` defines the following:

```php
namespace Zend\Expressive\Csrf;

use Psr\Http\Message\ServerRequestInterface;

interface CsrfGuardFactoryInterface
{
    public function createGuardFromRequest(ServerRequestInterface $request) : CsrfGuardInterface;
}
```

We provide the following concrete factories:

- `Zend\Expressive\Csrf\SessionCsrfGuardFactory`
- `Zend\Expressive\Csrf\FlashCsrfGuardFactory`

You will need to map the appropriate one to the
`Zend\Expressive\Csrf\CsrfGuardFactoryInterface` service in your dependency
injection container. By default, we map this service to the
`SessionCsrfGuardFactory`.

You may also compose the `CsrfGuardFactoryInterface` directly in your own
middleware. When you do, you will have to manually use it to create the guard
instance prior to generating or validating a token:

```php
class SomeHandler implements MiddlewareInterface
{
    private $guardFactory;

    public function __construct(CsrfGuardFactoryInterface $guardFactory)
    {
        $this->guardFactory = $guardFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $guard = $this->guardFactory->createGuardFromRequest($request);
        // ...
    }
}
```
