# Error Reference

- `invalid start/end timestamp`: timestamp shape or numeric range is invalid.
- `end time must be greater than start time`: reversed or zero-duration caption.
- `caption overlaps line N`: caption begins before the preceding caption ends.
- `caption text cannot be empty`: lyric content is missing.
- `Generated SRT`: internal output validation failed before final write.

## Safe repair mode

`--repair` only normalizes BOM, CRLF/CR line endings, surrounding whitespace, period millisecond separators, one-to-three fraction digits, and missing hour fields. It never invents timestamps or text, reverses ranges, shifts overlaps, or sorts captions.

## Browser preview errors

The preview returns HTTP 422 with the authoritative source-line validation message. Browser row guidance is advisory only.
