# Plain-text automatic timing

Automatic detection preserves existing timestamped input. Untimed TXT files use one non-empty line per caption. During SRT conversion, the application generates sequential timestamps using the configured start offset, caption duration, and gap. Defaults are 0 seconds, 3 seconds, and 0 seconds. Generated timing is estimated and must be reviewed against the media.
