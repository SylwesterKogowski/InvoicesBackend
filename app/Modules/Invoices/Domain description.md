Invoices domain description
==
This document describes the invoices domain and it's vision.
The document describes general concepts, further information can be found by following the links.


<!-- TOC -->
* [Invoices domain description](#invoices-domain-description)
* [Domain vision](#domain-vision)
* [Core concepts](#core-concepts)
  * [Invoice](#invoice)
  * [Approval](#approval)
  * [Product](#product)
  * [Company](#company)
* [Design choices](#design-choices)
  * [Infrastructure layer below the domain layer](#infrastructure-layer-below-the-domain-layer)
  * [Entities as adapters of abstracted ORM models](#entities-as-adapters-of-abstracted-orm-models)
  * [Billed company](#billed-company)
  * [Protocol buffers endpoint data format](#protocol-buffers-endpoint-data-format)
    * [Downsides of protocol buffers](#downsides-of-protocol-buffers)
  * [Companies could be a separate module](#companies-could-be-a-separate-module)
<!-- TOC -->

# Domain vision

This domain is responsible for managing invoices, it is also the main domain of this project.
In particular to allow approval or rejection of invoices.
The core of this domain is the invoice entity.

# Core concepts

## Invoice

Invoice is a document that describes an event of selling products to some customer (_billed company_).
The invoice can be made from any company to any company (author's design choice).
The existence of invoice in the system means that it also exists in the real world and is a historical fact (author's interpretation).
The "invoice date" is understood as the issue date of the invoice.
The "invoice company" is understood as the company that issued the invoice.

Detailed description here: [link to entity](Domain/Entities/Invoice.php)
Future considerations are here: [link](Domain/Entities/InvoiceFuture.md)

## Approval

Approval is an act of approving or rejecting the invoice for payment (author's interpretation).
The event is handled by the Approval module [link to facade](../Approval/Application/ApprovalFacade.php).
The approval module decides whether the invoice is really approved or rejected.
The act initiator is the Invoice module.
The effects on the invoices are handled by the Invoice module.


## Product

Product is an item billable on some invoice.
Detailed description here: [link to entity](Domain/Entities/Product.php)

## Company

A company that either issues or is billed by some invoice.
Detailed description here: [link to entity](Domain/Entities/Company.php)
Future considerations are here: [link](Domain/Entities/CompanyFuture.md)

# Design choices

## Infrastructure layer below the domain layer
The architecture style is closed layers, with the infrastructure layer on the bottom.
Layers are: Web -> Api -> Domain -> Infrastructure.

The infrastructure layer is fully abstracted, so the domain layer doesn't depend i.e. from Eloquent or any other
framework, so we don't have to worry about the usual downsides of this architecture.
This allows to easily change the infrastructure layer without changing the domain layer.
Another version is recently popular - infrastructure layer above the domain layer, but it's not used here.
It's just a personal preference of the author, as it allows for easier enforcement over infrastructure layer to adapt
to the domain and not vice versa.
As a matter of fact, the DDD book describes the infrastructure layer below the domain layer, though it's an old book
now.

The downside is that it is a bit harder to test as you have to mock the infrastructure for the domain tests.
In my opinion, as far as tests should be treated neither better or worse than other code,
I think this is a point in which either approach is equally good if done correctly - either we sacrifice a bit of
testing code easiness or logic code easiness - we should treat both options equally and neither preferentially.
I chose this approach because I am personally biased to logic code easiness and I am quite used to this architecture
already.
I like the idea of enforcement on infrastructure requirements,
I will immediately see code in red if I forget to update the infrastructure layer after changes in the domain.

Anyway, both ways are fine by me.

## Entities as adapters of abstracted ORM models

Just a design choice, easily available if infrastructure is below, slightly harder if infrastructure is above, it allows
for easier lazy loading of dependencies (i.e. companies, products etc.), in the future it will allow for easier control
over the underlying ORM as well.
It also makes sure, that the storage state underneath lives together with the domain entity.
This in turn allows for underlying ORM (or whatever storage there may be) to detect and react to changes as they
happen to the domain entity, ORM's usually then update only changed fields. Otherwise the ORM would need to reload the
entity to check what was actually changed, this in turn can make unintended changes to the storage.
Imagine two users U1 and U2 which loaded entity E at the same time, one user changes a field A and another at the same
time changes field B. If we dont keep track of what was actually changed and just save the model, either one will
overwrite the changes of the other one. But in this model in the project, both changes will go through.
Simultaneous manipulation of objects is of course a more complicated topic than that, but for me this approach where
specific changes are done separately is a good start.
So we get some performance benefits here and some good start at handling simultaneous manipulation of objects for very
little work.
In the end, this may also allow for easier migration from Database to Event Sourcing,
because all changes are made directly on abstracted interface which is also used to maintain the state.

## Billed company

Billed company was not included in the original database model for the invoice.
It was included in this project, as it's a part of the task.
There was a company_id field in the original database model, but wasn't described whether it was the issuing company or
billed company, so I chose that it is now used as the issuing company, while new field billed_company_id is for the
billed company.

## Protocol buffers endpoint data format

Protocol Buffers where chosen here for following reasons:

* evolutionary abilities - Protocol Buffers specifies how to manage backwards (and forwards) compatibility of the API
  scheme.
* performance - Protocol Buffers is faster than JSON, as it is encoded binary without scheme (provided separately)
    * it's a bit of harder to debug in this way, but whatever.
* easy handling on the client side - Protocol Buffers have serializers and deserializers for almost all languages.
* Instead of publishing API specification, you can just publish specific connection library for the API in various
  langauges.
    * Protocol Buffers has code generators in almost all languages (js and PHP included), so we can easily generate a
      code for various projects.
    * The classes generated by Protocol Buffers may be used as DTOs.
* Protocol Buffers can work together with Apache Kafka, Apache Pulsar etc. and can be used for Event Sourcing.

Anyways, I just wanted to test this one out so here you go :P.

I saw some traces of events in this project, so I decided to use Protocol Buffers for the API.
Protocol Buffers seem to go really well with events, event sourcing and event-driven microservices, of course they are
good for any API as well.

### Downsides of protocol buffers

An unintuitive thing about generated classes as DTOs is that one needs to modify .proto files and regenerate the classes
in order to modify DTOs.
So it needs to be marked in the docs what's the proper way of updating the DTOs.

Another thing is that the code generated by Protocol Buffers is somewhat lacking,
it contains binary data, so it's prone to destruction by simple line formatting...
Would be nice to post some update of that to their github, or make a "fixer" for those files.

No hypermedia API for protocol buffers, it wouldn't make sense to make it with such binary data anyway.
On the other hand it shouldn't be a problem to publish the proto files and that could fix the problem for those knowing
where they are published.

There is a service description scheme in Protocol Buffers, but it's crude and simple and doesn't even support describing
various HTTP methods, so better to use something else if anything.

## Companies could be a separate module

And it most probably should be, though maybe not at this point.
I could easily imagine reports, separate edition, deletion and insertion, CMS and various other features that companies
would have in the future.
This simple class can grow into a big module of it's own (or maybe even a subdomain of it's own).
