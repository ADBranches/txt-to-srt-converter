# Adobe Premiere Pro Import Guide

1. Generate the SRT file with `bin/txt-to-srt`.
2. Open Adobe Premiere Pro and the target project.
3. Import the SRT into the Project panel like other media.
4. Drag the imported SRT onto the target sequence.
5. Choose the Subtitle caption format when prompted.
6. Select source timecode, playhead position, or timeline start as appropriate.
7. Confirm that Premiere creates a caption track and places all caption blocks.
8. Review timing, text, punctuation, and positioning.
9. Edit styling and caption text using Premiere caption controls.

The version 1.0.0 fixture was manually verified in Adobe Premiere Pro 2023. See `PREMIERE_IMPORT_TEST.md`.

## Browser output

The UI generates validated UTF-8, BOM-free SRT output verified through the Premiere import gate.
