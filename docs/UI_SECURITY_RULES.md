# UI Security Rules

## Upload limits

- Single file: 1,048,576 bytes.
- Batch count: 20 files.
- Batch total: 10,485,760 bytes.
- Allowed extension: `.txt`.
- Allowed MIME: `text/plain` after server-side inspection.
- Input must be valid UTF-8 after supported BOM handling.

## Request protection

- CSRF validation is required for every state-changing request.
- Session cookies are HttpOnly and SameSite=Strict.
- Secure cookies are enabled when HTTPS is active.
- Request methods and body sizes are validated.
- User content is HTML-escaped at output.

## Files and downloads

- User paths are never trusted.
- Filenames are reduced to safe generated basenames.
- Path traversal is rejected.
- Runtime files remain outside `public/`.
- Downloads use attachment disposition, explicit MIME, and `nosniff`.
- Temporary input and output files are deleted after use or expiry.

## Headers

Planned headers include Content-Security-Policy, X-Content-Type-Options, Referrer-Policy, Permissions-Policy, frame protection, and cache controls for sensitive responses.

## Validation boundary

Browser validation is advisory. PHP validation, conversion, SRT validation, and download authorization are authoritative.
