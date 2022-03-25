#!/usr/bin/env sh

set -e

curl -X POST -H "Content-Type:application/json" -d '{"markdown":"# Test Header\n\n**Test Bold**\n\n*Test Italic*\n\n* A\n* B\n* C\n\n1. A\n2. B\n3. C\n\n[Link](https://example.com)\n\n@color-test_color(Test Colored)\n"}' http://%host%:9501/convert
