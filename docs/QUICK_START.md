# Quick Start

## Requirements

- PHP 8.2 or newer
- Composer 2.7 or newer
- PHP extensions: ctype, filter, iconv, json, mbstring, and pcre

## Install

```bash
git clone https://github.com/ADBranches/txt-to-srt-converter.git
cd txt-to-srt-converter
composer install --no-dev --prefer-dist --optimize-autoloader
```

## Convert one file

```bash
bin/txt-to-srt templates/lyrics-template.txt --output-dir output
```

## Validate without writing

```bash
bin/txt-to-srt templates/lyrics-template.txt --dry-run
```

## Batch conversion

```bash
bin/txt-to-srt /path/to/lyrics --recursive --output-dir /path/to/subtitles
```

Existing SRT files are protected unless `--overwrite` is supplied. Use `--repair` only for supported minor formatting normalization.
