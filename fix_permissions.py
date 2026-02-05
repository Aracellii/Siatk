#!/usr/bin/env python3
import os
import re

def fix_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    original = content
    
    # Replace $user->can( dengan $user->hasPermissionTo(
    content = re.sub(r'\$user->can\(', r'$user->hasPermissionTo(', content)
    
    # Replace auth()->user()?->can( dengan auth()->user()?->hasPermissionTo(
    content = re.sub(r'auth\(\)->user\(\)\?->can\(', r'auth()->user()?->hasPermissionTo(', content)
    
    if content != original:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed: {filepath}")
        return True
    return False

# Process Policy files
policy_dir = 'app/Policies'
resource_dir = 'app/Filament/Resources'

count = 0
for directory in [policy_dir, resource_dir]:
    for root, dirs, files in os.walk(directory):
        for file in files:
            if file.endswith('.php'):
                filepath = os.path.join(root, file)
                if fix_file(filepath):
                    count += 1

print(f"\nTotal files fixed: {count}")
