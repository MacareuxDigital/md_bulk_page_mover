# Macareux Bulk Page Mover
A Concrete CMS package to move multiple pages at once.

## Installation

```bash
$ cd ./packages
$ git clone git@github.com:MacareuxDigital/md_bulk_page_mover.git md_bulk_page_mover
$ cd ../
$ ./concrete/bin/concrete c5:package-install md_bulk_page_mover
```

## What It Does

### Command (for v8 & v9): md:page:move 
Move multiple pages at once.
Run the following command-
```bash
$ ./concrete/bin/concrete md:page:move --from='/source_page_path' --to='/destination_page_path'
```

### Automated Job (for v8): Bulk Move Pages (bulk_move_pages)
Need to config the page `path_from` and to `path_to` in the config.
Run the following command-
```bash
$ ./concrete/bin/concrete c5:config set md_bulk_page_mover::settings.path_from '/path_from' --env=develop
$ ./concrete/bin/concrete c5:config set md_bulk_page_mover::settings.path_to '/path_to' --env=develop
```
or, upload the config file `application/config/md_bulk_page_mover/settings.php` to the config directory.
```php
return [
    'path_from' => '/path-from',
    'path_to' => '/path-to',
];
```

### Automated Task (for v9): Bulk Move Pages (bulk_move_pages)
Please input the page `path_from` and to `path_to` in the task options.