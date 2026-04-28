# -*- coding: utf-8 -*-
import sys
import os
sys.stdout.reconfigure(encoding='utf-8')

# The corruption chain has been identified as CP850 interpretation of UTF-8 bytes.
# Original UTF-8 bytes -> interpreted as CP850 -> encoded back to UTF-8
# We will reverse this: Decode UTF-8 string -> encode to CP850 bytes -> decode as UTF-8 string

def fix_mojibake(text):
    fixed_chars = []
    i = 0
    while i < len(text):
        char = text[i]
        # Check if this character might be part of the mojibake
        # Most Portuguese accented characters in UTF-8 start with 0xC3 (Ã)
        # 0xC3 in CP850 is ├. So mojibake usually starts with ├
        if char == '├':
            if i + 1 < len(text):
                next_char = text[i+1]
                try:
                    # Try to reverse the CP850 corruption for these two characters
                    corrupted_str = char + next_char
                    cp850_bytes = corrupted_str.encode('cp850')
                    original_char = cp850_bytes.decode('utf-8')
                    fixed_chars.append(original_char)
                    i += 2
                    continue
                except (UnicodeEncodeError, UnicodeDecodeError):
                    # If it fails, it might not be a mojibake sequence, just append the character
                    pass
        
        # There might be other prefixes, let's just try to fix word by word or line by line
        # Actually, let's just try to encode the whole string to cp850 and decode to utf-8
        # But we can't do it for the whole file because regular ASCII chars are the same in both.
        # But wait! If the file is UTF-8 encoded SQL, all ASCII characters (like 'INSERT INTO', etc.)
        # will encode to cp850 correctly (their bytes are < 128).
        # The ONLY issue is if the file contains legitimate characters > 128 that WERE NOT corrupted.
        # But given the file was generated with this wrong encoding, probably ALL characters > 128 are corrupted.
        fixed_chars.append(char)
        i += 1
    return "".join(fixed_chars)

def fix_line(line_bytes):
    # Decode the UTF-8 line (ignore errors to keep going)
    try:
        text = line_bytes.decode('utf-8')
    except UnicodeDecodeError:
        return line_bytes

    # If there are no non-ASCII characters or ├, we can skip
    if '├' not in text and 'Â' not in text: # There might be other mojibake from other prefixes like 0xC2 (┬)
        return line_bytes

    # Let's try the targeted replacement for known CP850 mojibake prefixes
    # UTF-8 characters like ç (c3a7), ã (c3a3), é (c3a9), á (c3a1), etc. start with C3
    # C3 in CP850 is ├
    # C2 in CP850 is ┬
    
    # We will find all substrings that can be encoded to cp850 and decoded as utf-8
    
    # A safer approach is to replace specifically the known sequences
    fixed = text
    return fixed.encode('utf-8')

# A much safer and comprehensive approach:
# Create a mapping of all possible corrupted sequences
replacements = {}
for i in range(128, 256):
    for j in range(128, 256):
        try:
            utf8_bytes = bytes([i, j])
            original_char = utf8_bytes.decode('utf-8')
            
            # How it got corrupted:
            cp850_str = utf8_bytes.decode('cp850')
            replacements[cp850_str] = original_char
        except UnicodeDecodeError:
            pass

# Also single byte corruptions if any (not common for UTF-8)
for i in range(194, 224): # Valid UTF-8 start bytes for 2-byte sequences
    try:
        utf8_bytes = bytes([i])
        # This wouldn't be valid UTF-8 alone, so ignore
    except: pass

print(f"Generated {len(replacements)} mojibake mappings.")

in_file = r'C:\laragon\www\App-Web\database\schema.sql'
out_file = r'C:\laragon\www\App-Web\database\schema_fixed.sql'

content = open(in_file, 'rb').read()
if content.startswith(b'\xef\xbb\xbf'):
    content = content[3:]

text = content.decode('utf-8', errors='replace')

# Apply replacements. Sort by length descending to be safe
for corrupted_str, correct_char in sorted(replacements.items(), key=lambda x: len(x[0]), reverse=True):
    if corrupted_str in text:
        # print(f"Replacing {repr(corrupted_str)} with {repr(correct_char)}")
        text = text.replace(corrupted_str, correct_char)

# Check results
idx = text.find('Articula')
if idx != -1:
    print(f"Sample: {text[idx:idx+25]}")
    
idx = text.find('saciedade')
if idx != -1:
    print(f"Sample 2: {text[idx:idx+50]}")

with open(out_file, 'wb') as f:
    f.write(text.encode('utf-8'))

print("Done generating fixed schema.")
