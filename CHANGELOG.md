# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.0.0alpha1 - TBD

### Added

- [#3](https://github.com/zendframework/zend-expressive-csrf/pull/3) adds
  support for PSR-15.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- [#3](https://github.com/zendframework/zend-expressive-csrf/pull/3) removes
  support for http-interop/http-middleware.

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
