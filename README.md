generateECF
===========

## Require
- Composer => https://getcomposer.org/
- PHP 7
- mySQL

## Install
- Clone project
- `cd project_name`
- Install dependency => `composer install`
- Create BDD => `php bin/console doctrine:database:create`
- Update BDD schema => `php bin/console doctrine:schema:update --force`
- Load fixtures => `php bin/console doctrine:fixtures:load`

## Doc
- [Import pages from existing PDF documents and use them as templates in FPDF](https://www.setasign.com/products/fpdi/manual/)
- [FPDF Pdf Generator](http://www.fpdf.org/)

A Symfony 3.4 project created on June 28, 2018, 12:29 pm.
