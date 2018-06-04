# Introduction

[Cross-Site Request Forgery](https://en.wikipedia.org/wiki/Cross-site_request_forgery)
(CSRF) is a security vector in which an unauthorized request is accepted by a server on
behalf of another user; it is essentially an exploit of the trust a site places
on a user's browser.

The typical mitigation is to create a one-time token that is transmitted as part
of the original form, and which must then be transmitted back by the client.
This token _expires_ after first submission or after a short amount of time,
preventing replays or further submissions. If the token provided does not match
what was originally sent, an error should be returned.

zend-expressive-csrf provides utilities for both generating CSRF tokens, as well
as validating them. Tokens are stored within a session, and expire after any
attempt to validate.
