============
Import rules
============

At least one import rule has to be defined so that the Centreon GLPI
module can import Network equipment and Computers from GLPI.

Rules are configured in *Configuration > Hosts > GLPI > Matching Rules
> Add*:

.. image:: /_static/user/selection_021.png
   :align: center

A cron job will automatically import the pieces of network equipment
and computers when:

* their names match *\*www\** ('*' being a wildcard)
* and they are located in Paris
* and they are taken in charge by the technician johndoe

The imported hosts will be monitored by the *Central* poller, tied to
the *srv-http* host template, put in the *Linux-Servers* host group
and *Paris_Web_Server* host category. Of course, services that are
linked to the *srv-http* template will be deployed.

.. note::

   * The top priority rules are the ones with the largest number of filters
   * The IP range refers to the IP to use while importing Computers,
     in case they have multiple network ports
   * The cronjob frequency (every minute by default) can be configured
     in the file */etc/cron.d/centreon-glpi*. For traffic and server
     load purposes, we strongly recommend you adjust the frequency
     once the Centreon GLPI module is proved to be up and running on
     your monitoring system

.. image:: /_static/user/selection_012.png
   :align: center

After a while, the host is imported and its services are deployed:

.. image:: /_static/user/selection_015.png
   :align: center

Host name / address combination is also synchronized if changes occur in GLPI.

.. image:: /_static/user/selection_014.png
   :align: center
