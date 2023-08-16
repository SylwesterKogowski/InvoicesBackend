This file describes predicted future and issues of the company entity.
==

## Possible issues
### Company changing it's data
If a company changes it's data in the future 
- since our company is currently an entity 
- if we will change the existing company data, all historical invoices will also have changed data
- an invoice is a document that doesn't change, how will we explain to the customer that a duplicate of historical invoice will now have different data?

Possible solution: make a history of company data changes, refer in invoices to a particular historical version of company data.
Another solution: create a 'succeeded_by' field in company entity, so that we can mark that a company has changed it's data and refer to the new company entity. 


## Possible future
### Company country
It is very important for invoices to mark the country of the company.
So we could add such a field to the company entity.

### Reports
There may come reports made from the list of companies, various sorting, filtering etc.

### CMS
Customer management system? Yea why not, this company can grow into such a system.
So maybe it should be a module of it's own.
