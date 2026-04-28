# -*- coding: utf-8 -*-
import sys
sys.stdout.reconfigure(encoding='utf-8')

# Build the correct mapping using the cp437 corruption chain
# Chain: original UTF-8 bytes -> read as cp437 -> encode result as UTF-8 -> that's in the file
# Fix: read file UTF-8 -> decode chars as cp437 bytes -> gives original UTF-8 -> correct!

# Build map for all chars whose UTF-8 is >= 2 bytes
chars = 'àáâãäåæçèéêëìíîïðñòóôõöùúûüýþÿÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖÙÚÛÜÝÞ°ºª€£¥§©®±×÷ '

replacements = []
for ch in chars:
    utf8_bytes = ch.encode('utf-8')
    if len(utf8_bytes) < 2:
        continue
    try:
        # Decode the UTF-8 bytes as if they were cp437 byte values
        cp437_chars = utf8_bytes.decode('cp437')
        # Encode those cp437 chars back to UTF-8 -> this is what appears in the file
        corrupted_bytes = cp437_chars.encode('utf-8')
        if corrupted_bytes != utf8_bytes:
            replacements.append((corrupted_bytes, utf8_bytes))
    except Exception:
        pass

# Sort longest first
replacements.sort(key=lambda x: -len(x[0]))

print("Replacement map:")
for corrupted, correct in replacements:
    print(f"  {corrupted.hex()} -> {correct.decode('utf-8')!r}")

# Load original schema
content = open(r'C:\laragon\www\App-Web\database\schema.sql', 'rb').read()
if content.startswith(b'\xef\xbb\xbf'):
    content = content[3:]

idx = content.find(b'Articula')
print(f"\nBEFORE: {repr(content[idx:idx+20])}")

fixed = content
for corrupted, correct in replacements:
    count = fixed.count(corrupted)
    if count > 0:
        fixed = fixed.replace(corrupted, correct)

idx = fixed.find(b'Articula')
print(f"AFTER: {fixed[idx:idx+25].decode('utf-8', errors='replace')}")

# Also check another word with ã
idx2 = fixed.find(b'emagrecimento')
# Check category 1 area - look for Emagrecimento
idx3 = fixed.find(b'saciedade')
print(f"Product text sample: {fixed[idx3:idx3+40].decode('utf-8', errors='replace')}")

with open(r'C:\laragon\www\App-Web\database\schema_fixed.sql', 'wb') as f:
    f.write(b'\xef\xbb\xbf')
    f.write(fixed)
print("\nschema_fixed.sql saved!")
