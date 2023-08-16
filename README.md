This is the interview assignment for Ingenious.

# How to run tests

I modified a bit the docker (I was doing this on Windows), so it needs to be rebuild.
Hopefully you have the same database user.
So, the domain description is [here](app/Modules/Invoices/Domain description.md), it describes what I've done and what happens there.
The full endpoint test is [here](tests/Feature/Modules/Invoices/Api/Web/InvoiceControllerTest.php).


# Modifications done

* 8.2 PHP version
* MariaDB build was changed a bit
  * different entrypoint
  * got rid of external data volume - it is internal now and will reset with container. I had some issues with the external volume on windows, don't know if it is my hard drive being bad or whatever, but it is just a test app so I external volume isn't really needed.
  * added a test database specific for testing, phpunit will use that

