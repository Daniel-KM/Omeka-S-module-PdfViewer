Document Viewer (module for Omeka S)
====================================

[Document Viewer] is a module for [Omeka S] that allows to display pdf files
with the browser reader or via an internal reader (the Mozilla library [pdf.js]),
at the choice of the admin.


Installation
------------

The module uses an external library, [pdf.js], so use the release zip to install
it, or use and init the source.

* From the zip

Download the last release [`DocumentViewer.zip`] from the list of releases (the
master does not contain the dependency), and uncompress it in the `modules`
directory.

* From the source and for development

If the module was installed from the source, rename the name of the folder of
the module to `DocumentViewer`, and go to the root module, and run:

```
    npm install
    cd node_modules/pdf.js
    npm install
    gulp dist
    cd ../..
    gulp
```

For update:

```
    npm update
    cd node_modules/pdf.js
    npm update
    gulp dist
    cd ../..
    gulp
```


Config
------

All resources of Omeka S that are in pdf are automatically displayed by the
DocumentViewer, so you have nothing to do.

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

See online issues on the [module issues] page on GitHub.


License
-------

This module is published under the [CeCILL v2.1] licence, compatible with
[GNU/GPL] and approved by [FSF] and [OSI].

In consideration of access to the source code and the rights to copy, modify and
redistribute granted by the license, users are provided only with a limited
warranty and the software’s author, the holder of the economic rights, and the
successive licensors only have limited liability.

In this respect, the risks associated with loading, using, modifying and/or
developing or reproducing the software by the user are brought to the user’s
attention, given its Free Software status, which may make it complicated to use,
with the result that its use is reserved for developers and experienced
professionals having in-depth computer knowledge. Users are therefore encouraged
to load and test the suitability of the software as regards their requirements
in conditions enabling the security of their systems and/or data to be ensured
and, more generally, to use and operate it in the same conditions of security.
This Agreement may be freely reproduced and published, provided it is not
altered, and that no provisions are either added or removed herefrom.

The [pdf.js] library is published under the [Apache] license.


Contact
-------

See documentation on the [pdf.js] on its site.

Current maintainers of the module:
* Daniel Berthereau (see [Daniel-KM])


Copyright
---------

Javascript library [pdf.js]:

* Copyright Mozilla, 2011-2017

Module DocumentViewer for Omeka S:

* Copyright Daniel Berthereau, 2017


[Document Viewer]: https://github.com/Daniel-KM/Omeka-S-module-DocumentViewer
[Omeka S]: https://omeka.org/s
[pdf.js]: https://mozilla.github.io/pdf.js
[module issues]: https://github.com/Daniel-KM/Omeka-S-module-DocumentViewer/issues
[CeCILL v2.1]: https://www.cecill.info/licences/Licence_CeCILL_V2.1-en.html
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html
[FSF]: https://www.fsf.org
[OSI]: http://opensource.org
[Apache]: https://github.com/mozilla/pdf.js/blob/master/LICENSE
[Daniel-KM]: https://github.com/Daniel-KM "Daniel Berthereau"
