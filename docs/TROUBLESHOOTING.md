# Troubleshooting

## File imports as `.srt.txt`

Enable file extensions in the operating system and ensure the final filename ends with `.srt` only.

## Invalid UTF-8

Save the TXT source as UTF-8. Repair mode removes a UTF-8 BOM but does not convert unknown legacy encodings.

## Invalid timestamp

Use strict `HH:MM:SS,mmm` formatting. Example: `00:01:05,250`.

## Reversed timestamp

The end must be later than the start. The converter will not reverse timestamps automatically.

## Overlapping captions

Make the next caption start at or after the previous caption ends.

## Empty lyric

Every non-empty source row needs start time, end time, and lyric text separated by pipe characters.

## Existing output skipped

This is overwrite protection. Use `--overwrite` only when replacement is intended.

## Premiere does not show captions

Confirm the SRT was dragged onto the sequence, select Subtitle format, verify the sequence start choice, and inspect the caption track visibility.

## Get diagnostic output

```bash
bin/txt-to-srt input.txt --dry-run
composer quality
```
