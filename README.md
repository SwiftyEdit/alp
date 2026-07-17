# alp.mod

**Advanced Language Pack** — a [SwiftyEdit](https://swiftyedit.org) plugin that
expands or overwrites contents from the default language packs in
`/languages/*`.

## Requirements

- SwiftyEdit build 25-148 or newer (see `info.json`)

## Install

Copy the folder alp into the directory /plugins/.
As soon as you call up this addon in the backend for the first time, 
the database is created and the module is ready.

### Expand mode

If we use expand mode, SwiftyEdit will use the 
global injection file `/plugins/alp/global/index.php`

### Overwrite mode

In overwrite mode we use advanced language files from the plugin directory. 
These language files will be build by this addon and stored in the directory 
`/data/includes/`.
SwiftyEdit will automatically include these files as they are generated.

Example language file: `/data/includes/lang_de.php`

## License

GPL-3.0 — see [license.txt](license.txt).
