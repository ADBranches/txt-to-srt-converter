# UI Requirements

## Scope

Version 1.1.0 adds a server-rendered PHP browser interface while preserving the version 1.0.0 CLI and conversion engine.

## Primary journeys

1. Open the editor and paste timestamped lyrics.
2. Upload one UTF-8 `.txt` file and load it into the editor.
3. Preview captions and exact source-line validation errors.
4. Enable safe repair explicitly when required.
5. Convert validated content and download a `.srt` attachment.
6. Upload multiple TXT files, review truthful results, and download successful outputs.

## Acceptance criteria

- Server-side PHP validation is authoritative.
- JavaScript only enhances the experience; core conversion works without it.
- Existing parser, validators, formatter, repairer, and SRT validator are reused.
- Unicode lyrics and punctuation are preserved.
- Failed requests never appear as successful conversions.
- Existing CLI behavior and version 1.0.0 remain compatible.
- The interface meets WCAG 2.2 AA targets.

## States

- Empty: starter guidance and a copy-ready example.
- Loading: textual progress indicator with `aria-live` support.
- Success: explicit completion message and download action.
- Error: concise summary plus exact source-line details when available.
- Warning: repair and overwrite implications stated before submission.
