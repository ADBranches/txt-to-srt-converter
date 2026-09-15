# Supported TXT Input Format

## Canonical syntax

Each non-empty source line represents one caption:

```text
START_TIME | END_TIME | LYRIC_TEXT
```

## Timecode format

The canonical timecode is `HH:MM:SS,mmm`.

- `HH` must contain at least two digits.
- `MM` must be between `00` and `59`.
- `SS` must be between `00` and `59`.
- `mmm` must contain exactly three digits.
- A comma separates seconds from milliseconds.

## Parsing rules

- UTF-8 input is required.
- Blank lines are ignored.
- Leading and trailing whitespace around fields is trimmed.
- The first two pipe characters delimit the three required fields.
- Pipe characters after the second delimiter remain part of the lyric text.
- Lyric punctuation and Unicode characters are preserved.
- Caption numbering is generated automatically.

## Valid example

```text
00:00:05,000 | 00:00:08,500 | First lyric line
00:00:08,500 | 00:00:12,000 | Second lyric line
```

## Generated SRT structure

Each output block contains a generated index, a time range, caption text and a blank separator line.


## Supported timestamp variants

The parser accepts `H:MM:SS`, `MM:SS`, optional one-to-three digit fractions, and either a period or comma fraction separator. Every accepted value is normalized to `HH:MM:SS,mmm` in the SRT output.
