This file describes predicted future and issues of the invoice entity.
==

## Possible issues
### Invoice changing it's data
Once issued invoice shouldn't have the possibility to change it's data.
From this there is an exception that young companies or accountants make mistakes and will need to change the invoice data.
Changing data is possible as long as the invoice was not sent to the customer, 
specific fields can be still changed afterwards if a note was sent to the recipient (that is subject to regulations).


Possible solution: make a history of invoice data changes, refer in invoices to a particular historical version of invoice data.
Also, make a procedure of approving of changing the invoice data.


## Possible future
### Invoice country
If the system is for international customers, then various countries will have different invoices.

### Invoice types
Pro-forma invoices, interim and final invoices etc. are used commonly by companies.
We could support such invoices as well.

### Tax liability date
Depending on the type of invoice and regulations, the tax liability date may be different.
It is however important for accountants to know the tax liability date of the invoice because it is the date when the invoice is included in the tax declaration.

### Exports to accounting systems
In the future we may need to export the invoices to accounting systems.

### Invoice reports
In the future we may need to generate reports of invoices. The client of those reports will usually be the accounting department,
thus it may be a different user than the usual system user.

### Invoice payments
In the future we may need to register payments to invoices.
