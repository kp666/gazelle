#!/bin/bash

set -euo pipefail

LIB_DIR="$(dirname "${BASH_SOURCE[0]}")/../../lib"
SOURCE="${LIB_DIR}/devel.example.config.php"
TARGET="${LIB_DIR}/override.config.php"

[ -f ${TARGET} ] && exit 0
echo "GENERATING GAZELLE CONFIG..."
(
    perl -ple 's/""/q{"} . qx(head \/dev\/urandom|tr -dc 0-9A-Za-z|head -c 16) . q{"}/e' "${SOURCE}"
    date +"define('SITE_LAUNCH_YEAR', %Y);"
) > "${TARGET}"
