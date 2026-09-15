# TXT-to-SRT Converter

A Noviq Labs Ltd PHP utility for converting timestamped lyric TXT files into Premiere-compatible SRT subtitle files.

## Project scope

- Parse one timestamped lyric entry per TXT line.
- Validate missing, malformed, reversed, and overlapping timestamps.
- Produce sequential UTF-8 SRT captions.
- Preserve Unicode lyric text and punctuation.
- Protect existing output files from accidental overwrites.

## Runtime requirements

- PHP 8.2 or newer
- Composer 2.7 or newer
- Required PHP extensions: ctype, filter, iconv, json, mbstring and pcre

## Supported TXT syntax

```text
HH:MM:SS,mmm | HH:MM:SS,mmm | Caption text
```

Example:

```text
00:00:05,000 | 00:00:08,500 | First lyric line
00:00:08,500 | 00:00:12,000 | Second lyric line
```

## Expected SRT output

```srt
1
00:00:05,000 --> 00:00:08,500
First lyric line

2
00:00:08,500 --> 00:00:12,000
Second lyric line
```

## Development status

Phase 1 establishes the repository, input specification, validation rules, brand tokens and representative samples.


## CLI usage

```bash
composer install
bin/txt-to-srt samples/lyrics-valid.txt output/lyrics.srt
bin/txt-to-srt samples/lyrics-valid.txt output/lyrics.srt --overwrite
```

The converter accepts UTF-8 TXT input, ignores blank lines, normalizes supported timestamps, preserves Unicode text, reports exact malformed line numbers, and protects existing outputs by default.


## Validation and safe repair

Use `--repair` for explicitly supported minor formatting normalization:

```bash
bin/txt-to-srt input.txt output.srt --repair
```

Repair mode never guesses missing timestamps or text, reverses timestamps, shifts overlaps, or sorts captions. Every generated SRT is internally validated before it is atomically written.
