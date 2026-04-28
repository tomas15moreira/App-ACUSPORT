# -*- coding: utf-8 -*-
import sys
import io

def fix_sql_file():
    in_file = r'C:\laragon\www\App-Web\database\schema.sql'
    out_file = r'C:\laragon\www\App-Web\database\schema_fixed.sql'
    
    # Read raw bytes
    with open(in_file, 'rb') as f:
        content = f.read()

    # The file has a BOM?
    if content.startswith(b'\xef\xbb\xbf'):
        content = content[3:]

    # Decode as UTF-8
    text = content.decode('utf-8', errors='replace')
    
    # Direct mappings that we know are broken
    # Based on our CP850 analysis:
    # ç (U+00E7) = UTF-8: c3 a7 -> CP850: ├º -> UTF-8 encoded in file: e2 94 9c c2 ba
    # õ (U+00F5) = UTF-8: c3 b5 -> CP850: ├Á -> UTF-8 encoded in file: e2 94 9c c3 81
    # ã (U+00E3) = UTF-8: c3 a3 -> CP850: ├ú -> UTF-8 encoded in file: e2 94 9c c3 ba
    # í (U+00ED) = UTF-8: c3 ad -> CP850: ├¡ -> UTF-8 encoded in file: e2 94 9c c2 a1
    # é (U+00E9) = UTF-8: c3 a9 -> CP850: ├® -> UTF-8 encoded in file: e2 94 9c c2 ae
    # á (U+00E1) = UTF-8: c3 a1 -> CP850: ├í -> UTF-8 encoded in file: e2 94 9c c3 ad
    # ó (U+00F3) = UTF-8: c3 b3 -> CP850: ├│ -> UTF-8 encoded in file: e2 94 9c e2 94 82
    # ê (U+00EA) = UTF-8: c3 aa -> CP850: ├¬ -> UTF-8 encoded in file: e2 94 9c c2 ac
    # ú (U+00FA) = UTF-8: c3 ba -> CP850: ├║ -> UTF-8 encoded in file: e2 94 9c e2 95 91
    # à (U+00E0) = UTF-8: c3 a0 -> CP850: ├á -> UTF-8 encoded in file: e2 94 9c c3 a1
    # Ç (U+00C7) = UTF-8: c3 87 -> CP850: ├ç -> UTF-8 encoded in file: e2 94 9c c3 a7
    # É (U+00C9) = UTF-8: c3 89 -> CP850: ├ë -> UTF-8 encoded in file: e2 94 9c c3 ab
    # Â (U+00C2) = UTF-8: c3 82 -> CP850: ├é -> UTF-8 encoded in file: e2 94 9c c3 a9
    # Ê (U+00CA) = UTF-8: c3 8a -> CP850: ├è -> UTF-8 encoded in file: e2 94 9c c3 a8
    # Ó (U+00D3) = UTF-8: c3 93 -> CP850: ├ô -> UTF-8 encoded in file: e2 94 9c c3 b4
    # Í (U+00CD) = UTF-8: c3 8d -> CP850: ├ì -> UTF-8 encoded in file: e2 94 9c c3 ac
    # º (U+00BA) = UTF-8: c2 ba -> CP850: ┬║ -> UTF-8 encoded in file: e2 94 ac e2 95 91
    # ª (U+00AA) = UTF-8: c2 aa -> CP850: ┬¬ -> UTF-8 encoded in file: e2 94 ac c2 ac
    
    # We will build exactly this list programmatically to be comprehensive for Portuguese:
    
    chars_to_fix = [
        'ç', 'õ', 'ã', 'í', 'é', 'á', 'ó', 'ê', 'ú', 'à', 'Ç', 'É', 'Â', 'Ê', 'Ó', 'Í', 'º', 'ª', 'â', 'ô'
    ]
    
    replacements = {}
    for char in chars_to_fix:
        utf8_bytes = char.encode('utf-8')
        corrupted_cp850_str = utf8_bytes.decode('cp850')
        replacements[corrupted_cp850_str] = char

    # Sort by length descending, though they should all be 2 chars
    for corrupted_str, correct_char in sorted(replacements.items(), key=lambda x: len(x[0]), reverse=True):
        if corrupted_str in text:
            # print(f"Replacing {repr(corrupted_str)} with {repr(correct_char)}")
            text = text.replace(corrupted_str, correct_char)

    # Let's check results
    idx = text.find('Articula')
    if idx != -1:
        print(f"Sample 1: {text[idx:idx+25]}")
        
    idx = text.find('saciedade')
    if idx != -1:
        print(f"Sample 2: {text[idx:idx+50]}")
        
    idx = text.find('recupera')
    if idx != -1:
        print(f"Sample 3: {text[idx:idx+50]}")

    with open(out_file, 'wb') as f:
        f.write(text.encode('utf-8'))
        
    print("Fix complete.")

fix_sql_file()
