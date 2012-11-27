============
From sources
============

*************
Prerequisites
*************

The following packages are required:

* php-soap
* `Centreon <http://www.centreon.com/Content-Download/donwload-centreon>`_ (> 2.3.0)
* `Centreon CLAPI <http://www.centreon.com/Content-Download/download-centreon-clapi>`_ (> 1.3.0)
* `GLPI <http://www.glpi-project.org/spip.php?article41>`_ (> 0.83)
* `GLPI Web Services plugin <http://plugins.glpi-project.org/spip.php?article94>`_ (> 1.3.0)

************
Installation
************

The prerequisites must be installed before going any further. Once
everything is installed, download the Centreon GLPI module: TODO:
insert URL

Extract the package and run the installer::

  $ tar zxf centreon-glpi-x.x.x.tar.gz
  $ cd centreon-glpi-x.x.x
  $ ./install.sh

.. image:: /_static/installation/install_glpi_1.png
   :align: center

Enter the directory */etc/centreon*.

.. image:: /_static/installation/install_glpi_2.png
   :align: center

You can now finish the installation on the Centreon web interface:

.. image:: /_static/installation/install_glpi_3.png
   :align: center
