In this folder schemas for api messages are stored in protocol buffers format.
https://protobuf.dev/programming-guides/proto3/

From those schemas, message serialization/deserialization code is generated for this project and for the receiving clients.
Use the compile-protocol-buffers command in the root folder to execute Dto code generation.

WARNING: when adding new fields, never reuse old numbers of fields, always add new fields with new numbers.
That is the requirement of API evolution and backward compatibility.
