Document Viewer (module for Omeka S)
====================================

[![Build Status](https://travis-ci.org/Daniel-KM/Omeka-S-module-DocumentViewer.svg?branch=master)](https://travis-ci.org/Daniel-KM/Omeka-S-module-DocumentViewer)

[Document Viewer] is a module for [Omeka S] that allows to display common
document standard formats (pdf and office ones). Supported format are:

- Portable document format (pdf)
- OpenDocument Text (odt)
- OpenDocument Spreadsheet (ods)
- OpenDocument Presentation (odp)

The pdf is integrated via the library of Mozilla [pdf.js] and the office formats
via [WebODF].


Installation
------------

Uncompress files and rename module folder "DocumentViewer".

Then install it like any other Omeka module.

Options can be set differently for each site:

- in site settings for the integration of the player;
- in the json file "config.json" of pdf.js for the player itself: copy and
  update it in a folder named "document-viewer" inside the folder of the
  theme;
- via the helper: to use an alternative config for some items, add an option
  `config` with its url in the array of arguments passed to the viewer (see
  below).

See below the notes for more info.

* Javascript library "pdf.js"

The distribution release of the javascript library [pdf.js] is included in the
folder `asset/vendor/pdfjs/`.

If you want a more recent release, clone it inside asset/pdfjs. In command line,
from the root of the module, the first time:

```
    npm install
    gulp
```

The next times:

```
    npm update
    gulp
```

IMPORTANT: currently, the integrated pdf.js library is sligthy modified. See
`view/common/pdf-viewer.phtml` for details.


Usage
-----

All resources of Omeka S that are in pdf are automatically displayed by the
DocumentViewer, so you have nothing to do.


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
[Omeka]: https://omeka.org
[pdf.js]: https://mozilla.github.io/pdf.js
[WebODF]: https://github.com/kogmbh/WebODF
[distribution]: https://github.com/mozilla/pdf.js
[module issues]: https://github.com/Daniel-KM/Omeka-S-module-DocumentViewer/issues
[CeCILL v2.1]: https://www.cecill.info/licences/Licence_CeCILL_V2.1-en.html
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html
[FSF]: https://www.fsf.org
[OSI]: http://opensource.org
[Apache]: https://github.com/mozilla/pdf.js/blob/master/LICENSE
[Daniel-KM]: https://github.com/Daniel-KM "Daniel Berthereau"
