# Adobe Premiere Pro Import Test

## Purpose

Verify that `tests/Fixtures/expected-valid.srt` imports into Adobe Premiere Pro and creates a usable caption track.

## Automated preparation status

- Fixture generated from `tests/Fixtures/valid-lyrics.txt`: Pending automated verification
- SRT structure validation: Pending automated verification
- UTF-8 validation: Pending automated verification
- Sequential numbering: Pending automated verification
- Timestamp validation: Pending automated verification

## Manual Premiere test procedure

1. Copy `tests/Fixtures/expected-valid.srt` to the editing workstation.
2. Open Adobe Premiere Pro.
3. Open or create a short test sequence lasting at least 12 seconds.
4. Import `expected-valid.srt` into the Project panel.
5. Drag the imported SRT onto the sequence timeline.
6. Confirm that Premiere creates a caption track.
7. Play the sequence and verify all three captions and their timing.
8. Confirm that punctuation and text remain intact.

## Manual evidence record

- Tester:
- Test date:
- Adobe Premiere Pro version:
- Operating system:
- Sequence name:
- Import succeeded: PENDING
- Caption track created: PENDING
- Caption count is 3: PENDING
- Timing usable: PENDING
- Text and punctuation preserved: PENDING
- Screenshot or recording location:
- Final manual result: PENDING

## Gate rule

Phase 5 must not be marked complete until the manual evidence record contains a verified PASS result.
