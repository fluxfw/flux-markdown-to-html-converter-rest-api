#!/usr/bin/env sh

set -e

curl -X POST -H "Content-Type:application/json" -d '{"id":{"markdown":"**Test**\n"}}' http://%host%:9501/convert-multiple
