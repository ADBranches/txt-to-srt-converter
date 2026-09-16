# UI User Flows

## Paste and convert

1. Open `/`.
2. Paste canonical timestamped lyrics.
3. Preview or convert.
4. Server validates every row.
5. On success, download a Premiere-ready SRT.
6. On failure, retain input and show exact errors.

## Upload and convert

1. Select one `.txt` file.
2. Validate extension, MIME, size, filename, and UTF-8.
3. Load text into the editor.
4. Preview, optionally enable repair, and convert.
5. Download only after internal SRT validation succeeds.

## Repair

1. User explicitly enables repair mode.
2. Interface explains supported changes.
3. Server applies only BOM, line-ending, whitespace, separator, fraction-padding, and missing-hour normalization.
4. Reversed times, overlaps, missing text, and missing timestamps still fail.

## Batch

1. Select up to 20 TXT files totaling no more than 10 MiB.
2. Validate each file independently.
3. Show passed, skipped, and failed counts.
4. Allow individual downloads and a ZIP containing successful SRT files only.
