.. include:: /Includes.rst.txt


.. _admin-manual:

Administrator Manual
====================


Installation
------------

After installing the extension you only need to take care to put the template of the additional view into the correct directory.

.. code-block:: typoscript

    plugin.tx_eventnews {
      view {
        templateRootPaths {
            10 = EXT:your-site-package/Resources/Private/Extensions/Eventnews/Templates/
        }
      }
    }

And having the copy of the file `EXT:eventnews/Resources/Private/Templates/News/Month.html` at `EXT:your-site-package/Resources/Private/Extensions/Eventnews/Templates/News/Month.html`.

Configuration
-------------

The following TypoScript options are available: ::

    startingpointLocation
    startingpointOrganizer


New news records as event
^^^^^^^^^^^^^^^^^^^^^^^^^

By using the following code block in the Page TsConfig it is possible to hide the checkbox "Is event" and have new news records created as event:

.. code-block:: typoscript

    tx_news.newRecordAsEvent = 1
    TCEFORM.tx_news_domain_model_news.is_event {
      disabled = 1
    }
