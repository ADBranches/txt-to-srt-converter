# CLI Reference

```bash
bin/txt-to-srt INPUT [--output-dir DIR] [--recursive] [--overwrite] [--repair] [--dry-run] [--help]
```

- `INPUT`: one TXT file or a directory.
- `--output-dir DIR`: configurable destination. Recursive runs preserve relative directories.
- `--recursive`: include nested directories.
- `--overwrite`: replace existing SRT outputs.
- `--repair`: enable explicitly supported safe repairs.
- `--dry-run`: parse and validate without writing SRT files.
- `--help`: print usage.

Exit codes: `0` success or safe skips, `1` conversion failure, `2` invalid usage. Output summaries report passed, skipped, and failed totals. ANSI colors use Noviq brand mappings only when stdout is an interactive terminal and are disabled automatically when redirected.
