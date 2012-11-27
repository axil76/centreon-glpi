==============
GLPI ticketing
==============

Tickets of incident type can be sent to GLPI thanks to the Event
handler mechanism.

If Event handler does not sound familiar to you, please refer to your
Monitoring engine documentation.

The Centreon GLPI module comes with two new event handler commands
that you may use for triggering tickets: *glpi-ticket-host* and
*glpi-ticket-service*.

Edit your hosts or host templates and define the GLPI event handler:

.. image:: /_static/user/selection_019.png
   :align: center

Likewise, edit your service or service templates:

.. image:: /_static/user/selection_018.png
   :align: center

Generate your configuration files and restart/reload the monitoring
engine. In a distributed environment with multiple pollers, make sure
to copy the following:

* /usr/lib/nagios/plugins/glpi-ticket
* /usr/lib/nagios/plugins/glpi

The end result in GLPI:

.. image:: /_static/user/selection_020.png
   :align: center
