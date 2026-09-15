# Validation Rules

## Source validation

1. The source file must exist and be readable.
2. The source must contain valid UTF-8 text.
3. Every non-empty line must contain start time, end time and lyric text.
4. Each timestamp must match `HH:MM:SS,mmm` after supported normalization.
5. Minutes and seconds must be within `00` to `59`.
6. Milliseconds must be within `000` to `999`.
7. Caption text must not be empty after trimming.

## Timeline validation

1. Every end time must be greater than its start time.
2. Captions must be arranged in ascending start-time order.
3. A caption must not start before the preceding caption ends.
4. Adjacent captions may share a boundary, where the next start equals the previous end.

## Output validation

1. Caption indexes must begin at 1 and remain sequential.
2. SRT milliseconds must use a comma separator.
3. Every timing line must contain ` --> `.
4. Caption blocks must be separated by one blank line.
5. Output must be UTF-8 without a byte-order mark.
6. The output file must use the `.srt` extension.

## Failure behavior

- Invalid input must not create a partial final SRT file.
- Error messages must identify the source line where possible.
- Existing outputs must not be overwritten unless explicitly authorized.
- No failed operation may be reported as successful.
