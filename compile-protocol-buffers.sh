#!/bin/sh
echo "Compiling protocol buffer definitions into code serialization classes"
dev-tools\protocol-buffers\linux-x86_64\bin\protoc --php_out=./ --proto_path=./app/Modules/Invoices/Api/MessageSchemas app/Modules/Invoices/Api/MessageSchemas\*.proto 