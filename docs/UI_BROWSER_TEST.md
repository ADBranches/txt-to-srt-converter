# UI Browser Test

## Automated gate

Run `composer test:ui-http` while the development server is available. Verify editor, preview, conversion, download, batch, Unicode, error status, and no-JavaScript form submission.

## Manual viewport gate

Test at 375 px mobile width, 768 px tablet width, and 1440 px desktop width. Confirm no horizontal page overflow, readable controls, visible focus, and usable editor and batch workflows. Record date, browser, operating system, viewport, and PASS or FAIL before release approval.

## Completed manual verification



- Verification date: 2026-09-16T05:54:20Z

- Mobile 375x812: PASS

- Tablet 768x1024: PASS

- Desktop 1440x900: PASS

- Editor page styling: PASS

- Batch page styling: PASS

- No blocking horizontal overflow: PASS

- Editor and batch controls usable: PASS

- Static CSS and JavaScript delivery: PASS

- Nonblocking observation: favicon.ico currently returns HTTP 404.
