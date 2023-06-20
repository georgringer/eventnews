.. include:: /Includes.rst.txt


.. _admin-manual:

Administrator Manual
====================


Installation
------------

After installing the extension you only need to take care to put the template of the additional view into the correct directory.

If you use the default templates of EXT:news
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you are using the default templates of the news extension and haven't adopted those at all, all you need to do is using this TypoScript:

.. code-block:: typoscript

    plugin.tx_news {
      view {
        templateRootPaths {
            0 = {$plugin.tx_news.view.templateRootPath}
            1 = EXT:eventnews/Resources/Private/Templates/
        }
      }
    }

Using your own templates
^^^^^^^^^^^^^^^^^^^^^^^^

Set the view directories for the content element:

.. code-block:: typoscript

    tt_content.eventnews_newsmonth.20.view {
      templateRootPaths.10 = EXT:my_sitepackage/Resources/Private/Templates/Eventnews
      partialRootPaths.10 = EXT:my_sitepackage/Resources/Private/Partials/Eventnews
    }

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
