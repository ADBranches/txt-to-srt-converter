# UI Architecture

## Boundary

```text
Browser -> public/index.php -> Router -> Controller -> Existing application/domain services -> Response
```

## Principles

- Server-rendered PHP is the baseline.
- Progressive JavaScript provides line numbers, live advisory feedback, preview updates, and batch progress.
- Controllers coordinate requests but do not duplicate conversion rules.
- The existing parser and validators remain authoritative.
- Generated output is internally validated before download.
- Runtime files are stored outside the public directory and removed after use.

## Planned routes

- `GET /`: editor.
- `POST /preview`: validation and caption preview.
- `POST /convert`: single conversion.
- `GET /download`: one-time validated SRT download.
- `GET /health`: minimal operational status.

## Layers

- `public/`: web entry point and public assets only.
- `src/Http/`: request, response, routing, session, CSRF, upload, and headers.
- `src/Controller/`: browser workflow coordination.
- `src/Application/`: use cases that reuse the established engine.
- `resources/views/`: escaped server-rendered templates.
- `resources/css/` and `resources/js/`: progressive presentation assets.
