Pdf Viewer (module for Omeka S)
===============================

> __New versions of this module and support for Omeka S version 3.0 and above
> are available on [GitLab], which seems to respect users and privacy better
> than the previous repository.__

[Pdf Viewer] is a module for [Omeka S] that allows to display pdf files
with the browser reader or via the customizable internal reader, the same Mozilla
library [pdf.js], at the choice of the admin and site admins.


Installation
------------

The module uses an external library, [pdf.js], so use the release zip to
install it, or use and init the source.

See general end user documentation for [installing a module].

* From the zip

Download the last release [PdfViewer.zip] from the list of releases (the
master does not contain the dependency), and uncompress it in the `modules`
directory.

* From the source and for development

If the module was installed from the source, rename the name of the folder of
the module to `PdfViewer`, and go to the root module, and run:

```sh
composer install --no-dev
```

The assets are a slightly modified version of the generic viewer. They can be
rebuild if needed with the script "build.php", that is automatically run from
composer during install.

```sh
npm install
cd node_modules/pdf.js
npm install
gulp dist
# The package version for composer contains the standard minified version from
# the directory "build" and the "generic" viewer.
cd ../..
rm -rf /tmp/pdf.js
cp -r node_modules/pdf.js/build/generic/ /tmp/pdf.js/
rm /tmp/pdf.js/web/compressed.tracemonkey-pldi-09.pdf
cp node_modules/pdf.js/build/dist/build/pdf.min.js /tmp/pdf.js/build/
cp node_modules/pdf.js/build/dist/build/pdf.worker.min.js /tmp/pdf.js/build/
rm -rf asset/vendor/pdf.js
cp -r /tmp/pdf.js asset/vendor
php -f data/scripts/build.php
# For release.
tar czf /tmp/pdf.js.tar.gz /tmp/pdf.js
```

In some cases, when using an old version of pdf.js, the argument `--legacy-peer-deps`
should be added to npm.


Config
------

All resources of Omeka S that are in pdf are automatically displayed by the
Pdf Viewer, so you have nothing to do.

Options can be set differently for each site:

- in site settings for the integration of the player;
- in the json file "config.json" of pdf.js for the player itself: copy and
  update the files in `asset/vendor/pdfjs` and/or the file `common/pdf-viewer-inline.phtml`
  inside your theme.


Warning
-------

Use it at your own risk.

It’s always recommended to backup your files and your databases and to check
your archives regularly so you can roll back if needed.


Troubleshooting
---------------

See online issues on the [module issues] page on GitLab.


License
-------

This module is published under the [CeCILL v2.1] license, compatible with
[GNU/GPL] and approved by [FSF] and [OSI].

This software is governed by the CeCILL license under French law and abiding by
the rules of distribution of free software. You can use, modify and/ or
redistribute the software under the terms of the CeCILL license as circulated by
CEA, CNRS and INRIA at the following URL "http://www.cecill.info".

As a counterpart to the access to the source code and rights to copy, modify and
redistribute granted by the license, users are provided only with a limited
warranty and the software’s author, the holder of the economic rights, and the
successive licensors have only limited liability.

In this respect, the user’s attention is drawn to the risks associated with
loading, using, modifying and/or developing or reproducing the software by the
user in light of its specific status of free software, that may mean that it is
complicated to manipulate, and that also therefore means that it is reserved for
developers and experienced professionals having in-depth computer knowledge.
Users are therefore encouraged to load and test the software’s suitability as
regards their requirements in conditions enabling the security of their systems
and/or data to be ensured and, more generally, to use and operate it in the same
conditions as regards security.

The fact that you are presently reading this means that you have had knowledge
of the CeCILL license and that you accept its terms.

The [pdf.js] library is published under the [Apache] license.


Copyright
---------

Javascript library [pdf.js]:

* Copyright Mozilla, 2011-2021

Module Pdf Viewer for Omeka S:

* Copyright Daniel Berthereau, 2017-2021 (see [Daniel-KM] on GitLab)


[Pdf Viewer]: https://gitlab.com/Daniel-KM/Omeka-S-module-PdfViewer
[Omeka S]: https://omeka.org/s
[pdf.js]: https://mozilla.github.io/pdf.js
[Installing a module]: https://omeka.org/s/docs/user-manual/modules/#installing-modules
[PdfViewer.zip]: https://gitlab.com/Daniel-KM/Omeka-S-module-PdfViewer/-/releases
[module issues]: https://gitlab.com/Daniel-KM/Omeka-S-module-PdfViewer/-/issues
[CeCILL v2.1]: https://www.cecill.info/licences/Licence_CeCILL_V2.1-en.html
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html
[FSF]: https://www.fsf.org
[OSI]: http://opensource.org
[Apache]: https://github.com/mozilla/pdf.js/blob/master/LICENSE
[GitLab]: https://gitlab.com/Daniel-KM
[Daniel-KM]: https://gitlab.com/Daniel-KM "Daniel Berthereau"
