.. include:: /Includes.rst.txt
.. _users-manual:

============
Users manual
============

Using this extension is very simple if you are used to the extension *news* already!.

.. only:: html

    .. contents::
        :local:
        :depth: 1

Additional fields
=================

The following fields are added:


Is Event
--------

This checkbox defines if a news item is rendered as event item. Only if
set, the additional event fields are displayed!


Event end
---------

An optional end date can be defined for an event. If set, the event will
be shown for every day starting with the start time (which is set in the
regular :guilabel:`Date & time` field) until the given end date.


Full day
--------

This checkbox can be used to style events in a different way. There is
no additional functionality implemented.


Organizer
---------

This relation can be used to add an organization to an event.

The organizer record itself contains only the basic fields:

*   Title
*   Description


Location
--------

This relation can be used to add a location to an event.

The location record itself contains the following fields:

*   Title
*   Description
*   Street, ZIP, City, State, Municipality, Country
*   Phone, Email
*   Latitude & Longitude
*   Link

The country is stored as its ISO 3166-1 alpha-2 code, e.g. :code:`AT`. None of
the address fields are rendered by the templates shipped with the extension,
they are meant to be used in your own templates.

Organizer Simple
----------------

A text field to set an organizer without using a relation.

Location Simple
---------------

A text field to set a location without using a relation.

Additional views
================

The plugin of *EXT:news* ist extended by an additional view,
called :guilabel:`Month view`.

..  attention::
    An additional setting *Event Restriction* defines which records are
    rendered in this view. Available options are:

    *   No constraint (all records)
    *   Only events
    *   Only non events
