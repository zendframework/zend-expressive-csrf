# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.0.0 - 2018-03-15

### Added

- [#5](https://github.com/zendframework/zend-expressive-csrf/pull/5) adds
  support for PSR-15.

### Changed

- Updates minimum supported versions of both zend-expressive-session and
  zend-expressive-flash to 1.0.0.

### Deprecated

- Nothing.

### Removed

- [#5](https://github.com/zendframework/zend-expressive-csrf/pull/5) and
  [#3](https://github.com/zendframework/zend-expressive-csrf/pull/3) remove
  support for http-interop/http-middleware and
  http-interop/http-server-middleware.

### Fixed

- Nothing.

## 0.1.1 - 2017-10-11

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Fixes the `ConfigProvider` to correctly use the `SessionCsrfGuardFactory` as
  the default `CsrfGuardFactoryInterface` implementation; this ensures that no
  additional dependencies are required to use the extension initially.

## 0.1.0 - 2017-10-10

### Added

- Everything.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.
