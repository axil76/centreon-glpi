=================
GLPI WS and CLAPI
=================

******************
GLPI Configuration
******************

Web services have to be configured properly in GLPI.

Go to *Plugins > Webservices* and create a new Webservice
configuration and fill up the form like this:

.. image:: /_static/user/glpi_ws_conf.png
   :align: center

If you use multiple monitoring pollers, make sure they all match the IP range.

**********************
Centreon Configuration
**********************

Now, you have to configure Centreon GLPI. Go to *Administration > Options > GLPI > API*.

Fill up the form like this:

.. image:: /_static/user/api_conf_2.png
   :align: center

Clicking on the *Test API* button should get you the message
*Successfully called Web Service*, otherwise make sure everything is
configured properly.

GLPI WS Server::

  URL: Web URL that points to GLPI application
  API secure username/password (optional): must match what is specified on GLPI
  Admin login/password: a GLPI super administrator login/password

Centreon CLAPI::

  Admin login/password: a Centreon administrator login/password with access to front end
  Restart CLAPI after import: whether or not CLAPI will export configuration files and restart scheduling engines after the host import process (if hosts were actually imported)
