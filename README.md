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

### Command: md:page:move 
Move multiple pages at once.
Run the following command-
```bash
$ ./concrete/bin/concrete md:page:move --from='/source_page_path' --to='/destination_page_path'
```