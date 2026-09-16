# UI Deployment Guide

Require PHP 8.2 or later, Composer 2, and the extensions declared in `composer.json`. Set the production web root to `public`, use HTTPS, enable secure session cookies, and keep configuration, repository metadata, logs, uploads, and temporary files outside the public web root.

Development: `bin/install-requirements --development`, then `bin/serve-ui`.

Production: `bin/install-requirements`, then `composer check-platform-reqs --no-dev`.
